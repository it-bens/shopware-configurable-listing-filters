<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\RangeInterval\Aggregate;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

/**
 * @extends EntityCollection<RangeIntervalListingFilterConfigurationTranslationEntity>
 */
class RangeIntervalListingFilterConfigurationTranslationCollection extends EntityCollection
{
    /**
     * @api
     */
    public function getApiAlias(): string
    {
        return 'itb_listing_filter_configuration_translation_collection_range_interval';
    }

    /**
     * @api
     */
    protected function getExpectedClass(): string
    {
        return RangeIntervalListingFilterConfigurationTranslationEntity::class;
    }
}
