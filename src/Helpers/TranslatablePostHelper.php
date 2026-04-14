<?php

namespace NextDeveloper\Blogs\Helpers;

use Illuminate\Support\Facades\Log;
use NextDeveloper\Blogs\Database\Models\Posts;
use NextDeveloper\Commons\Helpers\SlugHelper;
use NextDeveloper\I18n\Services\I18nTranslationService;

/**
 * Provides translation capabilities for blog posts.
 *
 * This trait is the single source of truth for translating a Posts model.
 * Both TranslatePost and UpdatePostTranslations consume buildTranslatedPayload()
 * so new translations and updated translations stay field-for-field consistent.
 */
trait TranslatablePostHelper
{
    protected const MAX_CHUNK_LENGTH = 2000;

    protected const MAX_RETRIES = 3;

    protected const MIN_COMPLETION_RATIO = 0.8;

    /**
     * Fields copied verbatim from the source post onto every translation.
     *
     * @return array<string, mixed>
     */
    protected function getCommonFields(): array
    {
        return [
            'iam_account_id' => $this->model->iam_account_id,
            'iam_user_id' => $this->model->iam_user_id,
            'header_image' => $this->model->header_image,
            'is_active' => $this->model->is_active,
            'is_locked' => $this->model->is_locked,
            'is_pinned' => $this->model->is_pinned,
            'is_draft' => $this->model->is_draft,
            'is_markdown' => $this->model->is_markdown,
            'common_category_id' => $this->model->common_category_id,
            'created_at' => $this->model->created_at,
            'updated_at' => $this->model->updated_at,
        ];
    }

    /**
     * Builds the full translated payload from $this->model for the given locale.
     * Returns translated title, body, abstract, meta_* fields, tags, and a slug
     * derived from the translated title. Missing source fields fall through as
     * null so the caller can merge with defaults as needed.
     *
     * @return array<string, mixed>
     */
    protected function buildTranslatedPayload(string $locale): array
    {
        $title = $this->translateField($this->model->title, $locale);
        $body = $this->translateBody($locale);
        $abstract = $this->translateField($this->model->abstract, $locale);

        $metaTitle = $this->model->meta_title
            ? $this->translateField($this->model->meta_title, $locale)
            : $title;

        $metaDescription = $this->translateField($this->model->meta_description, $locale);
        $metaKeywords = $this->translateField($this->model->meta_keywords, $locale);

        $payload = [
            'title' => $title ?? $this->model->title,
            'body' => $body ?? $this->model->body,
            'abstract' => $abstract ?? $this->model->abstract,
            'meta_title' => $metaTitle ?? $this->model->meta_title,
            'meta_description' => $metaDescription ?? $this->model->meta_description,
            'meta_keywords' => $metaKeywords ?? $this->model->meta_keywords,
            'tags' => $this->translateTags($this->model->tags, $locale),
        ];

        $payload['slug'] = SlugHelper::generate($payload['title'], Posts::class);

        return $payload;
    }

    /**
     * Translates a single scalar field. Returns the translated string, or the
     * original string as a fallback. Returns null only if the source was empty.
     */
    protected function translateField(?string $text, string $locale): ?string
    {
        if ($text === null || trim($text) === '') {
            return null;
        }

        try {
            $result = I18nTranslationService::translate(['text' => $text], $locale);
        } catch (\Throwable $e) {
            Log::warning('TranslatablePostHelper: field translation failed', [
                'locale' => $locale,
                'error' => $e->getMessage(),
            ]);

            return $text;
        }

        return $this->extractTranslation($result) ?? $text;
    }

    /**
     * Translates the tag list in a single call. Accepts array or scalar and
     * always returns a clean, trimmed array (possibly empty).
     *
     * @param  mixed  $tags
     * @return array<int, string>
     */
    protected function translateTags($tags, string $locale): array
    {
        if (empty($tags)) {
            return [];
        }

        if (! is_array($tags)) {
            $tags = [$tags];
        }

        $cleaned = array_values(array_filter(array_map(
            fn ($tag): string => trim(str_replace('"', '', (string) $tag)),
            $tags
        )));

        if (empty($cleaned)) {
            return [];
        }

        try {
            $result = I18nTranslationService::translate(['text' => implode(',', $cleaned)], $locale);
        } catch (\Throwable $e) {
            Log::warning('TranslatablePostHelper: tag translation failed', [
                'locale' => $locale,
                'error' => $e->getMessage(),
            ]);

            return $cleaned;
        }

        $translated = $this->extractTranslation($result);

        if (! $translated) {
            return $cleaned;
        }

        return array_values(array_filter(array_map('trim', explode(',', $translated))));
    }

    /**
     * Translates the body, using paragraph-aware chunking when the text is
     * long. Returns null only when the source body is empty.
     */
    protected function translateBody(string $locale): ?string
    {
        if (empty($this->model->body)) {
            return null;
        }

        return $this->translateBodyWithChunking($locale);
    }

    /**
     * Normalizes the translation service response. The service returns a
     * model on a fresh translation and a plain array on cache hit, both of
     * which expose a `translation` value.
     *
     * @param  mixed  $result
     */
    protected function extractTranslation($result): ?string
    {
        if (! $result) {
            return null;
        }

        if (is_object($result) && isset($result->translation)) {
            return (string) $result->translation;
        }

        if (is_array($result) && ! empty($result['translation'])) {
            return (string) $result['translation'];
        }

        return null;
    }

    /**
     * Normalizes the alternates column which may be stored as JSON or array.
     *
     * @param  mixed  $alternates
     * @return array<int, array<string, mixed>>
     */
    protected function normalizeAlternates($alternates): array
    {
        if (is_string($alternates)) {
            return json_decode($alternates, true) ?? [];
        }

        return is_array($alternates) ? $alternates : [];
    }

    /**
     * Handles chunked translation for long body content, retrying on failure
     * and recursively splitting if a returned translation looks truncated.
     */
    private function translateBodyWithChunking(string $target): string
    {
        $chunks = $this->splitBodyIntoChunks($this->model->body);
        $translatedChunks = [];

        foreach ($chunks as $index => $chunk) {
            $chunk = trim($chunk);

            if ($chunk === '') {
                continue;
            }

            $translatedChunks[] = $this->translateChunkWithRetry($chunk, $target, $index);
        }

        return implode("\n\n", $translatedChunks);
    }

    private function translateChunkWithRetry(string $chunk, string $target, int $index): string
    {
        for ($attempt = 1; $attempt <= self::MAX_RETRIES; $attempt++) {
            try {
                $translation = I18nTranslationService::translate(['text' => $chunk], $target);
                $translatedText = $this->extractTranslation($translation);

                if ($translatedText === null || $translatedText === '') {
                    throw new \RuntimeException('Empty translation received');
                }

                if ($this->translationLooksTruncated($chunk, $translatedText)) {
                    $translatedText = $this->retranslateInSubChunks($chunk, $target) ?? $translatedText;
                }

                usleep(100_000);

                return $translatedText;
            } catch (\Throwable $e) {
                Log::warning('Translation chunk failed', [
                    'chunk_index' => $index,
                    'attempt' => $attempt,
                    'error' => $e->getMessage(),
                ]);

                if ($attempt === self::MAX_RETRIES) {
                    Log::error("Failed to translate chunk {$index} after ".self::MAX_RETRIES.' attempts');

                    return $chunk;
                }

                usleep(500_000);
            }
        }

        return $chunk;
    }

    private function translationLooksTruncated(string $source, string $translated): bool
    {
        $inputLength = mb_strlen($source);

        if ($inputLength === 0) {
            return false;
        }

        return (mb_strlen($translated) / $inputLength) < self::MIN_COMPLETION_RATIO
            && $inputLength > 1000;
    }

    private function retranslateInSubChunks(string $chunk, string $target): ?string
    {
        $subChunks = $this->splitIntoSmallerChunks($chunk);
        $translations = [];

        foreach ($subChunks as $subChunk) {
            try {
                $result = I18nTranslationService::translate(['text' => $subChunk], $target);
                $text = $this->extractTranslation($result);

                if ($text !== null && $text !== '') {
                    $translations[] = trim($text);
                }
            } catch (\Throwable $e) {
                Log::warning('Sub-chunk translation failed', ['error' => $e->getMessage()]);
            }

            usleep(100_000);
        }

        return empty($translations) ? null : implode(' ', $translations);
    }

    private function splitIntoSmallerChunks(string $text): array
    {
        $sentences = preg_split('/(?<=[.!?])\s+/', $text);
        $chunks = [];
        $currentChunk = '';

        foreach ($sentences as $sentence) {
            if (mb_strlen($currentChunk.' '.$sentence) > 800) {
                if ($currentChunk !== '') {
                    $chunks[] = $currentChunk;
                }
                $currentChunk = $sentence;
            } else {
                $currentChunk = $currentChunk === '' ? $sentence : $currentChunk.' '.$sentence;
            }
        }

        if ($currentChunk !== '') {
            $chunks[] = $currentChunk;
        }

        return $chunks;
    }

    private function splitBodyIntoChunks(string $body): array
    {
        $paragraphs = preg_split('/\n\n+/', $body);
        $chunks = [];
        $currentChunk = '';

        foreach ($paragraphs as $paragraph) {
            $paragraph = trim($paragraph);

            if ($paragraph === '') {
                continue;
            }

            if (mb_strlen($currentChunk."\n\n".$paragraph) > self::MAX_CHUNK_LENGTH) {
                if ($currentChunk !== '') {
                    $chunks[] = $currentChunk;
                }
                $chunks[] = $paragraph;
                $currentChunk = '';
            } else {
                $currentChunk = $currentChunk === '' ? $paragraph : $currentChunk."\n\n".$paragraph;
            }
        }

        if ($currentChunk !== '') {
            $chunks[] = $currentChunk;
        }

        return array_map('trim', $chunks);
    }

    /**
     * @throws \InvalidArgumentException
     */
    protected function validateAlternate(array $alternate): void
    {
        if (empty($alternate['id'])) {
            throw new \InvalidArgumentException('Alternate post missing ID');
        }

        if (empty($alternate['locale'])) {
            throw new \InvalidArgumentException('Alternate post missing locale');
        }
    }

    /**
     * @param  mixed  $alternates
     * @return array<int, array<string, mixed>>
     */
    protected function cleanAlternates($alternates): array
    {
        $alternates = is_array($alternates) ? $alternates : [];

        return collect($alternates)
            ->map(function ($alt) {
                if (isset($alt['locale'])) {
                    $alt['locale'] = strtolower(trim($alt['locale']));
                }

                return $alt;
            })
            ->filter(function ($alt) {
                return ! empty($alt['id'])
                    && ! empty($alt['locale'])
                    && is_int($alt['id'])
                    && is_string($alt['locale']);
            })
            ->unique(function ($item) {
                return $item['locale'].'|'.$item['id'];
            })
            ->values()
            ->toArray();
    }
}
