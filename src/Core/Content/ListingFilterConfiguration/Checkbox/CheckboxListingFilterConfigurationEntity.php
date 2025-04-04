<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Checkbox;

use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\ListingFilterConfigurationEntity;

final class CheckboxListingFilterConfigurationEntity extends ListingFilterConfigurationEntity
{
    public const TWIG_TEMPLATE = '@Storefront/storefront/component/listing/filter/filter-boolean.html.twig';

    /**
     * @api
     */
    public function getAggregationName(): string
    {
        return $this->slugifyDalField($this->dalField);
    }

    public function getFilterName(): string
    {
        return $this->slugifyDalField($this->dalField);
    }
}
