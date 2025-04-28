<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\RangeInterval;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

/**
 * @extends EntityCollection<RangeIntervalListingFilterConfigurationEntity>
 */
class RangeIntervalListingFilterConfigurationCollection extends EntityCollection
{
    /**
     * @api
     */
    public function getApiAlias(): string
    {
        return 'itb_lfc_collection_range_interval';
    }

    /**
     * @api
     */
    protected function getExpectedClass(): string
    {
        return RangeIntervalListingFilterConfigurationEntity::class;
    }
}
