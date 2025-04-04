<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\ListingFilter\Checkbox\Storefront;

use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Checkbox\CheckboxListingFilterConfigurationEntity;

interface RenderDataBuilderInterface
{
    public function buildRenderData(CheckboxListingFilterConfigurationEntity $configurationEntity): RenderData;
}
