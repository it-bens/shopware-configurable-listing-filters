<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\MultiSelect;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

/**
 * @extends EntityCollection<MultiSelectListingFilterConfigurationEntity>
 */
class MultiSelectListingFilterConfigurationCollection extends EntityCollection
{
    /**
     * @api
     */
    public function getApiAlias(): string
    {
        return 'itb_listing_filter_configuration_collection_multi_select';
    }

    /**
     * @api
     */
    protected function getExpectedClass(): string
    {
        return MultiSelectListingFilterConfigurationEntity::class;
    }
}
