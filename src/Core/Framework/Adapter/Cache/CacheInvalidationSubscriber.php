<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Core\Framework\Adapter\Cache;

use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\ListingFilterConfigurationCollection;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\ListingFilterConfigurationRepositoryInterface;
use ITB\ITBConfigurableListingFilters\Core\Framework\Adapter\Cache\CacheInvalidation\EventFilterInterface;
use ITB\ITBConfigurableListingFilters\Core\Framework\Adapter\Cache\CacheInvalidation\FilterFieldInformation\FilterFieldInformationCollectorInterface;
use ITB\ITBConfigurableListingFilters\Core\Framework\Adapter\Cache\CacheInvalidation\FilterFieldInformationWithIds\FilterFieldInformationWithIdsCollectorInterface;
use ITB\ITBConfigurableListingFilters\Core\Framework\Adapter\Cache\CacheInvalidation\QueryExtenderInterface;
use ITB\ITBConfigurableListingFilters\Core\Framework\Adapter\Cache\CacheInvalidation\QueryStarterInterface;
use ITB\ITBConfigurableListingFilters\Core\Framework\Adapter\Cache\CacheInvalidationFailed\CacheInvalidationCategoryQueryBuildingFailedEvent;
use ITB\ITBConfigurableListingFilters\Core\Framework\Adapter\Cache\CacheInvalidationFailed\CacheInvalidationCategoryQueryExecutionFailedEvent;
use Psr\EventDispatcher\EventDispatcherInterface;
use Shopware\Core\Content\Product\SalesChannel\Listing\CachedProductListingRoute;
use Shopware\Core\Framework\Adapter\Cache\CacheInvalidator;
use Shopware\Core\Framework\DataAbstractionLayer\Event\EntityWrittenContainerEvent;
use Shopware\Core\Framework\Event\NestedEventCollection;

final class CacheInvalidationSubscriber
{
    public function __construct(
        private readonly EventFilterInterface $eventFilter,
        private readonly ListingFilterConfigurationRepositoryInterface $listingFilterConfigurationRepository,
        private readonly FilterFieldInformationCollectorInterface $filterFieldInformationCollector,
        private readonly FilterFieldInformationWithIdsCollectorInterface $filterFieldInformationWithIdsCollector,
        private readonly QueryStarterInterface $queryStarter,
        private readonly QueryExtenderInterface $queryExtender,
        private readonly CacheInvalidator $cacheInvalidator,
        private readonly EventDispatcherInterface $eventDispatcher,
    ) {
    }

    public function invalidate(EntityWrittenContainerEvent $event): void
    {
        if (! $event->getEvents() instanceof NestedEventCollection) {
            return;
        }

        $events = $this->eventFilter->filterEvents($event);
        if ($events === []) {
            return;
        }

        $listingFilterConfigurationCollection = ListingFilterConfigurationCollection::withListingFilterConfigurationRepository(
            $this->listingFilterConfigurationRepository,
            $event->getContext(),
            null
        );
        $filterFieldInformationCollection = $this->filterFieldInformationCollector->collect($listingFilterConfigurationCollection);
        $filterFieldInformationWithIdsCollection = $this->filterFieldInformationWithIdsCollector->collect(
            $event,
            $filterFieldInformationCollection
        );

        try {
            $query = $this->queryStarter->startQuery();
            $this->queryExtender->extendQuery($query, $filterFieldInformationWithIdsCollection, $event->getContext());
        } catch (\Throwable $throwable) {
            $this->eventDispatcher->dispatch(
                new CacheInvalidationCategoryQueryBuildingFailedEvent(
                    $filterFieldInformationWithIdsCollection,
                    $throwable,
                    $event,
                    $event->getContext()
                )
            );

            return;
        }

        try {
            /** @var array<string> $categoryIds */
            $categoryIds = $query->fetchFirstColumn();
        } catch (\Throwable $throwable) {
            $this->eventDispatcher->dispatch(
                new CacheInvalidationCategoryQueryExecutionFailedEvent($query, $throwable, $event, $event->getContext())
            );

            return;
        }

        if ($categoryIds === []) {
            return;
        }

        $tags = array_values(
            array_map(static fn (string $categoryId): string => CachedProductListingRoute::buildName($categoryId), $categoryIds)
        );
        $this->cacheInvalidator->invalidate($tags);
    }
}
