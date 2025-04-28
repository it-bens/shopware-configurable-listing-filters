<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\ListingFilter\RangeInterval\Storefront;

use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\RangeInterval\Aggregate\RangeIntervalListingFilterConfigurationIntervalEntity;
use ITB\ITBConfigurableListingFilters\ListingFilter\MultiSelect\Storefront\Element;

interface ElementBuilderInterface
{
    public function buildElement(RangeIntervalListingFilterConfigurationIntervalEntity $rangeIntervalEntity): Element;
}
