<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Core\Framework\Adapter\Cache\CacheInvalidation;

use Shopware\Core\Framework\DataAbstractionLayer\Dbal\QueryBuilder;

interface QueryStarterInterface
{
    public function startQuery(): QueryBuilder;
}
