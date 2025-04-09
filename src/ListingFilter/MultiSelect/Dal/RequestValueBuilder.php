<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\ListingFilter\MultiSelect\Dal;

use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\MultiSelect\MultiSelectListingFilterConfigurationEntity;
use Symfony\Component\HttpFoundation\Request;

final class RequestValueBuilder implements RequestValueBuilderInterface
{
    public function __construct(
        private readonly ValueFromRequestExtractorInterface $valueFromRequestExtractor,
    ) {
    }

    public function buildRequestValue(MultiSelectListingFilterConfigurationEntity $configurationEntity, Request $request): RequestValue
    {
        $valuesAsString = $this->valueFromRequestExtractor->extractValueFromRequest($request, $configurationEntity->getFilterName());

        /** @var array<string> $values */
        $values = explode('|', $valuesAsString);
        $values = array_filter($values);

        // The query parameter key will be prefixed with the filter name, so the filter name has to be removed.
        $values = array_map(static function (string $value) use ($configurationEntity): ?string {
            $pattern = '/' . preg_quote($configurationEntity->getFilterName(), '/') . '_/';

            return preg_replace($pattern, '', $value);
        }, $values);
        $values = array_filter($values);
        $values = array_values($values);

        return new RequestValue(array_values(array_filter($values)));
    }
}
