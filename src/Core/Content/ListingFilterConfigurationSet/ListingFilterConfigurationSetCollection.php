<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfigurationSet;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

/**
 * @extends EntityCollection<ListingFilterConfigurationSetEntity>
 */
class ListingFilterConfigurationSetCollection extends EntityCollection
{
    /**
     * @api
     */
    public function getApiAlias(): string
    {
        return 'itb_lfc_set_collection';
    }

    /**
     * @api
     */
    protected function getExpectedClass(): string
    {
        return ListingFilterConfigurationSetEntity::class;
    }
}
