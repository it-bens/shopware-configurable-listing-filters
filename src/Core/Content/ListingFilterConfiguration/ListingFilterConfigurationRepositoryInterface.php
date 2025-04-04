<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration;

use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Checkbox\CheckboxListingFilterConfigurationCollection;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\MultiSelect\MultiSelectListingFilterConfigurationCollection;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Range\RangeListingFilterConfigurationCollection;
use Shopware\Core\System\SalesChannel\SalesChannelContext;

interface ListingFilterConfigurationRepositoryInterface
{
    public function getCheckboxListingFilterConfigurations(
        SalesChannelContext $context,
        bool $loadSalesChannel = false
    ): CheckboxListingFilterConfigurationCollection;

    public function getMultiSelectListingFilterConfigurations(
        SalesChannelContext $context,
        bool $loadSalesChannel = false
    ): MultiSelectListingFilterConfigurationCollection;

    public function getRangeListingFilterConfigurations(
        SalesChannelContext $context,
        bool $loadSalesChannel = false
    ): RangeListingFilterConfigurationCollection;
}
