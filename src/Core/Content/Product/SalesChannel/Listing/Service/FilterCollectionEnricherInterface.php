<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Core\Content\Product\SalesChannel\Listing\Service;

use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\ListingFilterConfigurationCollection;
use Shopware\Core\Content\Product\SalesChannel\Listing\FilterCollection;
use Symfony\Component\HttpFoundation\Request;

interface FilterCollectionEnricherInterface
{
    public function enrichFilterCollection(
        ListingFilterConfigurationCollection $listingFilterConfigurationCollection,
        Request $request,
        FilterCollection $filterCollection
    ): void;
}
