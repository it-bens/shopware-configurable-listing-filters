<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\ListingFilter\Range\Dal;

use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Range\RangeListingFilterConfigurationEntity;
use Symfony\Component\HttpFoundation\Request;

final class RequestValueBuilder implements RequestValueBuilderInterface
{
    public function buildRequestValue(RangeListingFilterConfigurationEntity $configurationEntity, Request $request): RequestValue
    {
        $minValue = $request->query->get($configurationEntity->getMinimalValueAggregationName());
        $maxValue = $request->query->get($configurationEntity->getMaximalValueAggregationName());

        return new RequestValue(($minValue !== null) ? (int) $minValue : null, ($maxValue !== null) ? (int) $maxValue : null);
    }
}
