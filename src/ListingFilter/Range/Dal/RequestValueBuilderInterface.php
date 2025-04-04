<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\ListingFilter\Range\Dal;

use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Range\RangeListingFilterConfigurationEntity;
use Symfony\Component\HttpFoundation\Request;

interface RequestValueBuilderInterface
{
    public function buildRequestValue(RangeListingFilterConfigurationEntity $configurationEntity, Request $request): RequestValue;
}
