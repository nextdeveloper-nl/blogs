<?php

namespace NextDeveloper\Blogs\Helpers;

use NextDeveloper\Commons\Database\Models\Languages;
use NextDeveloper\I18n\Services\I18nTranslationService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Provides translation capabilities for blog posts
 *
 */
trait TranslatablePostHelper
{
    protected const MAX_CHUNK_LENGTH = 2000; // Reduced to ensure complete translations
    protected const MAX_RETRIES = 3;
    protected const MIN_COMPLETION_RATIO = 0.8; // Minimum ratio of output/input length to consider translation complete

    /**
     * Example alternate structure:
     * [
     *   'id' => 123,
     *   'locale' => 'en',
     *   'slug' => 'sample-post'
     * ]
     */
    /**
     * Returns array of fields that should be translated
     */
    protected function getTranslatableFields(): array
    {
        return [
            'title',
            'body',
            'meta_title',
            'meta_description'
        ];
    }

    /**
     * Returns common fields that should be copied to translations
     */
    protected function getCommonFields(): array
    {
        return [
            'iam_account_id'    => $this->model->iam_account_id,
            'iam_user_id'       => $this->model->iam_user_id,
            'header_image'      => $this->model->header_image,
            'is_active'         => $this->model->is_active,
            'is_locked'         => $this->model->is_locked,
            'is_pinned'         => $this->model->is_pinned,
            'is_draft'          => $this->model->is_draft,
            'is_markdown'       => $this->model->is_markdown,
            'common_category_id'=> $this->model->common_category_id,
            'created_at'        => $this->model->created_at,
            'updated_at'        => $this->model->updated_at
        ];
    }

    /**
     * Translates content for the given target locale
     */
    protected function translateContent(Languages $target): array
    {
        return collect($this->getTranslatableFields())
            ->filter(fn($field) => !empty($this->model->{$field}))
            ->mapWithKeys(function($field) use ($target) {
                try {
                    if ($field === 'body') {
                        return [$field => $this->translateBodyWithChunking($target->code)];
                    }

                    $translate = I18nTranslationService::translate(
                        ['text' => $this->model->{$field}],
                        $target->code
                    );

                    return [
                        $field => $translate ? $translate->translation : $this->model->{$field}
                    ];
                } catch (\Exception $e) {
                    Log::error("Translation failed for field {$field}: " . $e->getMessage());
                    return [$field => $this->model->{$field}];
                }
            })
            ->toArray();
    }

    /**
     * Handles chunked translation for long body content
     */
    private function translateBodyWithChunking(string $target): string
    {
        $chunks = $this->splitBodyIntoChunks($this->model->body);
        $translatedChunks = [];

        foreach ($chunks as $index => $chunk) {
            $retry = 0;
            $chunk = trim($chunk);

            if (empty($chunk)) continue;

            while ($retry < self::MAX_RETRIES) {
                try {
                    $translation = I18nTranslationService::translate(
                        ['text' => $chunk],
                        $target
                    );

                    if ($translation && !empty($translation->translation)) {
                        $translatedText = trim($translation->translation);

                        // Check if translation seems complete
                        $inputLength = mb_strlen($chunk);
                        $outputLength = mb_strlen($translatedText);
                        $completionRatio = $outputLength / $inputLength;

                        if ($completionRatio < self::MIN_COMPLETION_RATIO) {
                            // Translation appears truncated, try with a smaller chunk
                            if (mb_strlen($chunk) > 1000) {
                                $subChunks = $this->splitIntoSmallerChunks($chunk);
                                $subTranslations = [];

                                foreach ($subChunks as $subChunk) {
                                    $subTranslation = I18nTranslationService::translate(
                                        ['text' => $subChunk],
                                        $target
                                    );

                                    if ($subTranslation && !empty($subTranslation->translation)) {
                                        $subTranslations[] = trim($subTranslation->translation);
                                    }
                                    usleep(100000); // 100ms delay between sub-chunks
                                }

                                $translatedText = implode(" ", $subTranslations);
                            }
                        }

                        $translatedChunks[] = $translatedText;
                        usleep(100000); // 100ms delay
                        break;
                    }

                    throw new \Exception("Empty translation received");
                } catch (\Exception $e) {
                    $retry++;
                    Log::warning("Translation chunk {$index} failed (attempt {$retry}): " . $e->getMessage());

                    if ($retry === self::MAX_RETRIES) {
                        Log::error("Failed to translate chunk {$index} after " . self::MAX_RETRIES . " attempts");
                        $translatedChunks[] = $chunk; // Fallback to original content
                        break;
                    }

                    usleep(500000); // 500ms delay between retries
                }
            }
        }

        return implode("\n\n", $translatedChunks);
    }

    private function splitIntoSmallerChunks(string $text): array
    {
        $sentences = preg_split('/(?<=[.!?])\s+/', $text);
        $chunks = [];
        $currentChunk = '';

        foreach ($sentences as $sentence) {
            if (mb_strlen($currentChunk . " " . $sentence) > 800) {
                if (!empty($currentChunk)) {
                    $chunks[] = $currentChunk;
                }
                $currentChunk = $sentence;
            } else {
                $currentChunk = empty($currentChunk) ? $sentence : $currentChunk . " " . $sentence;
            }
        }

        if (!empty($currentChunk)) {
            $chunks[] = $currentChunk;
        }

        return $chunks;
    }

    private function splitBodyIntoChunks(string $body): array
    {
        $chunks = [];
        // Split by paragraphs first for better context
        $paragraphs = preg_split('/\n\n+/', $body);
        $currentChunk = '';

        foreach ($paragraphs as $paragraph) {
            $paragraph = trim($paragraph);
            if (empty($paragraph)) continue;

            // If adding this paragraph would exceed the limit
            if (mb_strlen($currentChunk . "\n\n" . $paragraph) > self::MAX_CHUNK_LENGTH) {
                if (!empty($currentChunk)) {
                    $chunks[] = $currentChunk;
                }
                $chunks[] = $paragraph; // Add paragraph as its own chunk
                $currentChunk = '';
            } else {
                $currentChunk = empty($currentChunk) ? $paragraph : $currentChunk . "\n\n" . $paragraph;
            }
        }

        if (!empty($currentChunk)) {
            $chunks[] = $currentChunk;
        }

        return array_map('trim', $chunks);
    }

    protected function validateAlternate(array $alternate): void
    {
        if (empty($alternate['id'])) {
            throw new \InvalidArgumentException('Alternate post missing ID');
        }

        if (empty($alternate['locale'])) {
            throw new \InvalidArgumentException('Alternate post missing locale');
        }
    }

    protected function cleanAlternates(mixed $alternates): array
    {
        $alternates = is_array($alternates) ? $alternates : [];

        return collect($alternates)
            ->map(function($alt) {
                if (isset($alt['locale'])) {
                    $alt['locale'] = strtolower(trim($alt['locale']));
                }
                return $alt;
            })
            ->filter(function($alt) {
                return !empty($alt['id']) &&
                       !empty($alt['locale']) &&
                       is_int($alt['id']) &&
                       is_string($alt['locale']);
            })
            ->unique(function($item) {
                return $item['locale'].'|'.$item['id'];
            })
            ->values()
            ->toArray();
    }


}
