<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\ListingFilter\MultiSelect\Dal;

use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\MultiSelect\MultiSelectListingFilterConfigurationEntity;
use Symfony\Component\HttpFoundation\Request;

interface RequestValueBuilderInterface
{
    public function buildRequestValue(MultiSelectListingFilterConfigurationEntity $configurationEntity, Request $request): RequestValue;
}
