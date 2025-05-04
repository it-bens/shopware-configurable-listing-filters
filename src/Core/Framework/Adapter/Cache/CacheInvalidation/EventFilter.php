<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Core\Framework\Adapter\Cache\CacheInvalidation;

use Shopware\Core\Framework\DataAbstractionLayer\Event\EntityWrittenContainerEvent;
use Shopware\Core\Framework\DataAbstractionLayer\Event\EntityWrittenEvent;
use Shopware\Core\Framework\MessageQueue\ScheduledTask\ScheduledTaskDefinition;
use Shopware\Core\Framework\Plugin\Aggregate\PluginTranslation\PluginTranslationDefinition;
use Shopware\Core\Framework\Plugin\PluginDefinition;

final class EventFilter implements EventFilterInterface
{
    private const ENTITIES_TO_IGNORE = [
        ScheduledTaskDefinition::ENTITY_NAME,
        // The entity written events of the plugins have to be ignored
        // because this could otherwise interfere with plugin lifecycle processes
        PluginDefinition::ENTITY_NAME,
        PluginTranslationDefinition::ENTITY_NAME,
    ];

    /**
     * @codeCoverageIgnore
     */
    public function __construct(
        private readonly EventFlattenerInterface $eventFlattener,
    ) {
    }

    public function filterEvents(EntityWrittenContainerEvent $event): array
    {
        /** @var array<EntityWrittenEvent> $events */
        $events = [];
        $this->eventFlattener->flattenEvents($event, $events);

        return array_filter(
            $events,
            fn (EntityWrittenEvent $event): bool => ! in_array($event->getEntityName(), self::ENTITIES_TO_IGNORE, true)
        );
    }
}
