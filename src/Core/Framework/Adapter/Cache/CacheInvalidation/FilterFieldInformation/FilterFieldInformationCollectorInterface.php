<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Core\Framework\Adapter\Cache\CacheInvalidation\FilterFieldInformation;

use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\ListingFilterConfigurationCollection;

interface FilterFieldInformationCollectorInterface
{
    public function collect(ListingFilterConfigurationCollection $listingFilterConfigurationCollection): FilterFieldInformationCollection;
}
