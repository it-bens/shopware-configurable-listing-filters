<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Range;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

/**
 * @extends EntityCollection<RangeListingFilterConfigurationEntity>
 */
class RangeListingFilterConfigurationCollection extends EntityCollection
{
    /**
     * @api
     */
    public function getApiAlias(): string
    {
        return 'itb_listing_filter_configuration_collection_range';
    }

    /**
     * @api
     */
    protected function getExpectedClass(): string
    {
        return RangeListingFilterConfigurationEntity::class;
    }
}
