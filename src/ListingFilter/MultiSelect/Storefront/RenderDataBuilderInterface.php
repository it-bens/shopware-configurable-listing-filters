<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\ListingFilter\MultiSelect\Storefront;

use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\MultiSelect\MultiSelectListingFilterConfigurationEntity;
use Shopware\Core\Framework\DataAbstractionLayer\Search\AggregationResult\AggregationResultCollection;

interface RenderDataBuilderInterface
{
    public function buildRenderData(
        MultiSelectListingFilterConfigurationEntity $configurationEntity,
        AggregationResultCollection $aggregationResults
    ): RenderData;
}
