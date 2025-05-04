<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Core\Framework\Adapter\Cache\CacheInvalidation;

use ITB\ITBConfigurableListingFilters\Core\Framework\Adapter\Cache\CacheInvalidation\FilterFieldInformationWithIds\FilterFieldInformationWithIdsCollection;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\Dbal\QueryBuilder;

interface QueryExtenderInterface
{
    public function extendQuery(
        QueryBuilder $query,
        FilterFieldInformationWithIdsCollection $filterFieldInformationWithIdsCollection,
        Context $context
    ): void;
}
