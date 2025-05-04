<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Core\Framework\Adapter\Cache;

use ITB\ITBConfigurableListingFilters\Core\Framework\Adapter\Cache\CacheInvalidationFailed\CacheInvalidationCategoryQueryBuildingFailedEvent;
use ITB\ITBConfigurableListingFilters\Core\Framework\Adapter\Cache\CacheInvalidationFailed\CacheInvalidationCategoryQueryExecutionFailedEvent;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class CacheInvalidationFailedSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly LoggerInterface $logger,
    ) {
    }

    /**
     * @return array<string, string>
     */
    public static function getSubscribedEvents(): array
    {
        return [
            CacheInvalidationCategoryQueryBuildingFailedEvent::class => 'onCacheInvalidationCategoryQueryBuildingFailed',
            CacheInvalidationCategoryQueryExecutionFailedEvent::class => 'onCacheInvalidationCategoryQueryExecutionFailedEvent',
        ];
    }

    public function onCacheInvalidationCategoryQueryBuildingFailed(CacheInvalidationCategoryQueryBuildingFailedEvent $event): void
    {
        $this->logger->error('Query building failed for cache invalidation failed', [
            'message' => $event->getException()
                ->getMessage(),
            'filterFieldInformationWithIds' => $event->getFilterFieldInformationWithIds(),
            'exception' => $event->getException(),
            'entityWrittenEvent' => $event->getEvent(),
            'context' => $event->getContext(),
        ]);
    }

    public function onCacheInvalidationCategoryQueryExecutionFailedEvent(CacheInvalidationCategoryQueryExecutionFailedEvent $event): void
    {
        $this->logger->error('Query execution failed for cache invalidation failed', [
            'message' => $event->getException()
                ->getMessage(),
            'query' => $event->getQueryBuilder(),
            'exception' => $event->getException(),
            'entityWrittenEvent' => $event->getEvent(),
            'context' => $event->getContext(),
        ]);
    }
}
