<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Range\Aggregate;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

/**
 * @extends EntityCollection<RangeListingFilterConfigurationTranslationEntity>
 */
class RangeListingFilterConfigurationTranslationCollection extends EntityCollection
{
    /**
     * @api
     */
    public function getApiAlias(): string
    {
        return 'itb_listing_filter_configuration_translation_collection_range';
    }

    /**
     * @api
     */
    protected function getExpectedClass(): string
    {
        return RangeListingFilterConfigurationTranslationEntity::class;
    }
}
