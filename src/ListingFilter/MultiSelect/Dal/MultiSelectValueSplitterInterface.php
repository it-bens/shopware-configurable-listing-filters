<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\ListingFilter\MultiSelect\Dal;

use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\ListingFilterConfigurationEntity;

interface MultiSelectValueSplitterInterface
{
    /**
     * @return array<string>
     */
    public function splitMultiSelectValue(string $valuesAsString, ListingFilterConfigurationEntity $filterConfigurationEntity): array;
}
