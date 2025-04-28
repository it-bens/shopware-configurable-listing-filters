<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\MultiSelect\Aggregate;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

/**
 * @extends EntityCollection<MultiSelectListingFilterConfigurationTranslationEntity>
 */
class MultiSelectListingFilterConfigurationTranslationCollection extends EntityCollection
{
    /**
     * @api
     */
    public function getApiAlias(): string
    {
        return 'itb_lfc_translation_collection_multi_select';
    }

    /**
     * @api
     */
    protected function getExpectedClass(): string
    {
        return MultiSelectListingFilterConfigurationTranslationEntity::class;
    }
}
