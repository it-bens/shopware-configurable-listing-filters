<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Core\Framework\Adapter\Cache\CacheInvalidation;

use Shopware\Core\Framework\DataAbstractionLayer\Event\EntityWrittenContainerEvent;
use Shopware\Core\Framework\DataAbstractionLayer\Event\EntityWrittenEvent;
use Shopware\Core\Framework\Event\NestedEventCollection;

final class EventFlattener implements EventFlattenerInterface
{
    public function flattenEvents(EntityWrittenContainerEvent $event, array &$events): void
    {
        foreach ($event->getEvents() ?? [] as $subEvent) {
            if ($subEvent instanceof EntityWrittenContainerEvent && $subEvent->getEvents() instanceof NestedEventCollection && count(
                $subEvent->getEvents()
            ) > 0) {
                $this->flattenEvents($subEvent, $events);
                continue;
            }

            if ($subEvent instanceof EntityWrittenEvent) {
                $events[] = $subEvent;
            }
        }
    }
}
