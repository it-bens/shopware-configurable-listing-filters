<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration;

use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Checkbox\CheckboxListingFilterConfigurationCollection;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\MultiSelect\MultiSelectListingFilterConfigurationCollection;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Range\RangeListingFilterConfigurationCollection;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\RangeInterval\RangeIntervalListingFilterConfigurationCollection;
use Shopware\Core\Framework\Context;

interface ListingFilterConfigurationRepositoryInterface
{
    public function getCheckboxListingFilterConfigurations(
        Context $context,
        ?string $salesChannelId,
        bool $loadSalesChannel = false
    ): CheckboxListingFilterConfigurationCollection;

    public function getMultiSelectListingFilterConfigurations(
        Context $context,
        ?string $salesChannelId,
        bool $loadSalesChannel = false
    ): MultiSelectListingFilterConfigurationCollection;

    public function getRangeIntervalListingFilterConfigurations(
        Context $context,
        ?string $salesChannelId,
        bool $loadSalesChannel = false
    ): RangeIntervalListingFilterConfigurationCollection;

    public function getRangeListingFilterConfigurations(
        Context $context,
        ?string $salesChannelId,
        bool $loadSalesChannel = false
    ): RangeListingFilterConfigurationCollection;
}
