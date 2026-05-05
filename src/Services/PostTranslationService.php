<?php

namespace NextDeveloper\Blogs\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;

/**
 * Translates blog post content via OpenAI chat completions.
 * Standalone — no dependency on the i18n module.
 */
class PostTranslationService
{
    protected Client $client;

    /**
     * @throws \Exception
     */
    public function __construct()
    {
        if (
            !config('i18n.services.openai.url')
            || !config('i18n.services.openai.key')
        ) {
            throw new \Exception('OpenAI is not configured (i18n.services.openai.url/key).');
        }

        $this->client = new Client([
            'base_uri' => config('i18n.services.openai.url'),
            'timeout' => 120,
            'connect_timeout' => 120,
            'headers' => [
                'Authorization' => 'Bearer ' . config('i18n.services.openai.key'),
                'Content-Type' => 'application/json',
            ],
        ]);
    }

    /**
     * Translates $text to $targetLocale, optionally declaring the source locale
     * so the model does not need to auto-detect. Returns the original text on
     * failure or when source equals target.
     */
    public function translate(string $text, string $targetLocale, ?string $sourceLocale = null): string
    {
        if (trim($text) === '') {
            return $text;
        }

        if ($sourceLocale !== null && $this->normalizeLocale($sourceLocale) === $this->normalizeLocale($targetLocale)) {
            return $text;
        }

        try {
            $response = $this->client->post('chat/completions', [
                'json' => [
                    'model' => 'gpt-4.1', // Best for translation tasks as of mid-2024, per OpenAI's documentation
                    'messages' => [
                        ['role' => 'system', 'content' => $this->buildPrompt($targetLocale, $sourceLocale)],
                        ['role' => 'user', 'content' => $text],
                    ],
                    'temperature' => 0.3, // Lower temperature for more deterministic output in translations
                    'max_tokens' => 8192, // Large token limit to accommodate long blog posts
                    'top_p' => 1.0, 
                ],
            ]);

            $result = json_decode($response->getBody()->getContents(), true);

            if (isset($result['choices'][0]['message']['content'])) {
                return trim($result['choices'][0]['message']['content']);
            }

            Log::error('[Blogs.PostTranslationService] Unexpected response shape', [
                'target_locale' => $targetLocale,
            ]);

            return $text;
        } catch (GuzzleException $e) {
            Log::error('[Blogs.PostTranslationService] API call failed', [
                'target_locale' => $targetLocale,
                'error' => $e->getMessage(),
            ]);

            return $text;
        }
    }

    protected function buildPrompt(string $targetLocale, ?string $sourceLocale = null): string
    {
        $from = $sourceLocale ? "from language '{$sourceLocale}' " : '';

        return <<<PROMPT
You are a professional blog content translator.

Your task:
- Translate the provided text {$from}to the language specified by ISO-639-1 code: '{$targetLocale}'
- Preserve the original meaning, tone, and intent
- Maintain all formatting, markdown syntax, HTML tags, and paragraph structure
- Keep placeholders (like :name, {variable}, %s) exactly as-is
- Keep code blocks, CLI commands, URLs, and brand/product names unchanged
- If the text is already in the target language, return it unchanged

Output rules:
- Output ONLY the translated text, nothing else
- Do NOT add explanations, comments, preamble, or quotation marks
PROMPT;
    }

    protected function normalizeLocale(string $locale): string
    {
        $locale = strtolower(trim($locale));

        if (preg_match('/^([a-z]{2,3})[-_]/', $locale, $matches)) {
            return $matches[1];
        }

        return $locale;
    }
}
