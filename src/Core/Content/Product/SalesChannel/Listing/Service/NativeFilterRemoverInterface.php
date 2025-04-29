<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Core\Content\Product\SalesChannel\Listing\Service;

use Shopware\Core\Content\Product\SalesChannel\Listing\FilterCollection;

interface NativeFilterRemoverInterface
{
    public function removeNativeFilters(FilterCollection $filterCollection, string $salesChannelId): void;
}
