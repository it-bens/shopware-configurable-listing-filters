<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\RangeInterval\Aggregate\Interval\Aggregate;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

/**
 * @extends EntityCollection<RangeIntervalListingFilterConfigurationIntervalTranslationEntity>
 */
class RangeIntervalListingFilterConfigurationIntervalTranslationCollection extends EntityCollection
{
    /**
     * @api
     */
    public function getApiAlias(): string
    {
        return 'itb_lfc_translation_collection_range_interval_interval';
    }

    /**
     * @api
     */
    protected function getExpectedClass(): string
    {
        return RangeIntervalListingFilterConfigurationIntervalTranslationEntity::class;
    }
}
