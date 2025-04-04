<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\ListingFilter\Checkbox\Dal;

use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Checkbox\CheckboxListingFilterConfigurationEntity;
use Shopware\Core\Content\Product\SalesChannel\Listing\Filter;

interface FilterBuilderInterface
{
    public function buildFilter(CheckboxListingFilterConfigurationEntity $filterConfiguration, RequestValue $requestValue): Filter;
}
