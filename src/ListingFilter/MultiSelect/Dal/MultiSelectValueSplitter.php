<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\ListingFilter\MultiSelect\Dal;

use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\ListingFilterConfigurationEntity;

final class MultiSelectValueSplitter implements MultiSelectValueSplitterInterface
{
    public function splitMultiSelectValue(string $valuesAsString, ListingFilterConfigurationEntity $filterConfigurationEntity): array
    {
        /** @var array<string> $values */
        $values = explode('|', $valuesAsString);
        $values = array_filter($values);

        // The query parameter key will be prefixed with the filter name, so the filter name has to be removed.
        $values = array_map(static function (string $value) use ($filterConfigurationEntity): ?string {
            $pattern = '/' . preg_quote($filterConfigurationEntity->getFilterName(), '/') . '_/';

            return preg_replace($pattern, '', $value);
        }, $values);
        $values = array_filter($values);

        return array_values($values);
    }
}
