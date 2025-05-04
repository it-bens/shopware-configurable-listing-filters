<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Core\Framework\Adapter\Cache\CacheInvalidation;

use Shopware\Core\Framework\DataAbstractionLayer\Event\EntityWrittenContainerEvent;
use Shopware\Core\Framework\DataAbstractionLayer\Event\EntityWrittenEvent;

interface EventFlattenerInterface
{
    /**
     * @param array<EntityWrittenEvent> $events
     */
    public function flattenEvents(EntityWrittenContainerEvent $event, array &$events): void;
}
