<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Core\Framework\Adapter\Cache\CacheInvalidation\FilterFieldInformationWithIds;

use ITB\ITBConfigurableListingFilters\Core\Framework\Adapter\Cache\CacheInvalidation\FilterFieldInformation\FilterFieldInformationCollection;
use Shopware\Core\Framework\DataAbstractionLayer\Event\EntityWrittenContainerEvent;

interface FilterFieldInformationWithIdsCollectorInterface
{
    public function collect(
        EntityWrittenContainerEvent $event,
        FilterFieldInformationCollection $filterFieldInformationCollection
    ): FilterFieldInformationWithIdsCollection;
}
