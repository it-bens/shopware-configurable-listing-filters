<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Checkbox\Aggregate;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

/**
 * @extends EntityCollection<CheckboxListingFilterConfigurationTranslationEntity>
 */
class CheckboxListingFilterConfigurationTranslationCollection extends EntityCollection
{
    /**
     * @api
     */
    public function getApiAlias(): string
    {
        return 'itb_listing_filter_configuration_translation_collection_checkbox';
    }

    /**
     * @api
     */
    protected function getExpectedClass(): string
    {
        return CheckboxListingFilterConfigurationTranslationEntity::class;
    }
}
