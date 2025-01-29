<?php

namespace NextDeveloper\Blogs\Helpers;

use NextDeveloper\I18n\Services\I18nTranslationService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

trait TranslatablePostHelper
{
    /**
     * Returns array of fields that should be translated
     */
    protected function getTranslatableFields(): array
    {
        return [
            'title',
            'slug',
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
            'tags'              => $this->model->tags,
            'iam_account_id'    => $this->model->iam_account_id,
            'iam_user_id'       => $this->model->iam_user_id,
            'astract'           => $this->model->astract,
            'header_image'      => $this->model->header_image,
            'is_active'         => $this->model->is_active,
            'is_locked'         => $this->model->is_locked,
            'is_pinned'         => $this->model->is_pinned,
            'is_draft'          => $this->model->is_draft,
            'is_markdown'       => $this->model->is_markdown,
            'common_domain_id'  => $this->model->common_domain_id,
            'common_category_id'=> $this->model->common_category_id,
        ];
    }

    /**
     * Translates content for the given target locale
     */
    protected function translateContent(string $target): array
    {
        // Add validation
        if (empty($target)) {
            throw new \InvalidArgumentException('Translation target locale cannot be empty');
        }

        return collect($this->getTranslatableFields())
            ->filter(fn($field) => !empty($this->model->{$field}))
            ->mapWithKeys(function($field) use ($target) {
                $translate = I18nTranslationService::translate(
                    ['text' => $this->model->{$field}],
                    $target
                );

                return [
                    $field => $translate ? $translate->translation : $this->model->{$field}
                ];
            })
            ->toArray();
    }

    protected function validateAlternate(array $alternate): void
    {
        if (empty($alternate['uuid'])) {
            throw new \InvalidArgumentException('Alternate post missing UUID');
        }

        if (empty($alternate['locale'])) {
            throw new \InvalidArgumentException('Alternate post missing locale');
        }
    }

    protected function cleanAlternates(array $alternates): array
    {
        return collect($alternates)
            ->filter(function($alt) {
                return !empty($alt['uuid']) && !empty($alt['locale']);
            })
            ->unique('uuid')
            ->values()
            ->toArray();
    }

    
}
