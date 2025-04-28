<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Checkbox;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

/**
 * @extends EntityCollection<CheckboxListingFilterConfigurationEntity>
 */
class CheckboxListingFilterConfigurationCollection extends EntityCollection
{
    /**
     * @api
     */
    public function getApiAlias(): string
    {
        return 'itb_lfc_collection_checkbox';
    }

    /**
     * @api
     */
    protected function getExpectedClass(): string
    {
        return CheckboxListingFilterConfigurationEntity::class;
    }
}
