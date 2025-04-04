<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\ListingFilter\Checkbox\Dal;

use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Checkbox\CheckboxListingFilterConfigurationEntity;
use Symfony\Component\HttpFoundation\Request;

final class RequestValueBuilder implements RequestValueBuilderInterface
{
    public function buildRequestValue(CheckboxListingFilterConfigurationEntity $configurationEntity, Request $request): RequestValue
    {
        $value = $request->query->get($configurationEntity->getFilterName(), '');
        if ($value === '') {
            return new RequestValue(false);
        }

        return new RequestValue($value === '1');
    }
}
