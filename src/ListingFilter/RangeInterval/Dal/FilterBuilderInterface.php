<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\ListingFilter\RangeInterval\Dal;

use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\RangeInterval\RangeIntervalListingFilterConfigurationEntity;
use Shopware\Core\Content\Product\SalesChannel\Listing\Filter;

interface FilterBuilderInterface
{
    public function buildFilter(RangeIntervalListingFilterConfigurationEntity $configurationEntity, RequestValue $requestValue): Filter;
}
