<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\ListingFilter\RangeInterval\Dal;

use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\RangeInterval\RangeIntervalListingFilterConfigurationEntity;
use Symfony\Component\HttpFoundation\Request;

interface RequestValueBuilderInterface
{
    public function buildRequestValue(RangeIntervalListingFilterConfigurationEntity $configurationEntity, Request $request): RequestValue;
}
