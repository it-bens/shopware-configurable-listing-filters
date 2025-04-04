<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\ListingFilter\Checkbox\Dal;

use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Checkbox\CheckboxListingFilterConfigurationEntity;
use Symfony\Component\HttpFoundation\Request;

interface RequestValueBuilderInterface
{
    public function buildRequestValue(CheckboxListingFilterConfigurationEntity $configurationEntity, Request $request): RequestValue;
}
