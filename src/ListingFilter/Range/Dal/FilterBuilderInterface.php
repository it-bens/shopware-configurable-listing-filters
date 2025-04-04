<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\ListingFilter\Range\Dal;

use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Range\RangeListingFilterConfigurationEntity;
use Shopware\Core\Content\Product\SalesChannel\Listing\Filter;

interface FilterBuilderInterface
{
    public function buildFilter(RangeListingFilterConfigurationEntity $configurationEntity, RequestValue $requestValue): Filter;
}
