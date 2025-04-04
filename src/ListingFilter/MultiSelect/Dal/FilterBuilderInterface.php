<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\ListingFilter\MultiSelect\Dal;

use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\MultiSelect\MultiSelectListingFilterConfigurationEntity;
use Shopware\Core\Content\Product\SalesChannel\Listing\Filter;

interface FilterBuilderInterface
{
    public function buildFilter(MultiSelectListingFilterConfigurationEntity $configurationEntity, RequestValue $requestValue): Filter;
}
