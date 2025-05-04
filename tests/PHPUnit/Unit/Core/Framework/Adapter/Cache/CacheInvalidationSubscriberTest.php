<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Test\PHPUnit\Unit\Core\Framework\Adapter\Cache;

use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Checkbox\CheckboxListingFilterConfigurationCollection;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\ListingFilterConfigurationRepositoryInterface;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\MultiSelect\MultiSelectListingFilterConfigurationCollection;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Range\RangeListingFilterConfigurationCollection;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\RangeInterval\RangeIntervalListingFilterConfigurationCollection;
use ITB\ITBConfigurableListingFilters\Core\Framework\Adapter\Cache\CacheInvalidation\EventFilterInterface;
use ITB\ITBConfigurableListingFilters\Core\Framework\Adapter\Cache\CacheInvalidation\FilterFieldInformation\FilterFieldInformationCollection;
use ITB\ITBConfigurableListingFilters\Core\Framework\Adapter\Cache\CacheInvalidation\FilterFieldInformation\FilterFieldInformationCollectorInterface;
use ITB\ITBConfigurableListingFilters\Core\Framework\Adapter\Cache\CacheInvalidation\FilterFieldInformationWithIds\FilterFieldInformationWithIdsCollection;
use ITB\ITBConfigurableListingFilters\Core\Framework\Adapter\Cache\CacheInvalidation\FilterFieldInformationWithIds\FilterFieldInformationWithIdsCollectorInterface;
use ITB\ITBConfigurableListingFilters\Core\Framework\Adapter\Cache\CacheInvalidation\QueryExtenderInterface;
use ITB\ITBConfigurableListingFilters\Core\Framework\Adapter\Cache\CacheInvalidation\QueryStarterInterface;
use ITB\ITBConfigurableListingFilters\Core\Framework\Adapter\Cache\CacheInvalidationFailed\CacheInvalidationCategoryQueryBuildingFailedEvent;
use ITB\ITBConfigurableListingFilters\Core\Framework\Adapter\Cache\CacheInvalidationFailed\CacheInvalidationCategoryQueryExecutionFailedEvent;
use ITB\ITBConfigurableListingFilters\Core\Framework\Adapter\Cache\CacheInvalidationSubscriber;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\EventDispatcher\EventDispatcherInterface;
use Shopware\Core\Content\Product\SalesChannel\Listing\CachedProductListingRoute;
use Shopware\Core\Framework\Adapter\Cache\CacheInvalidator;
use Shopware\Core\Framework\DataAbstractionLayer\Dbal\QueryBuilder;
use Shopware\Core\Framework\DataAbstractionLayer\Event\EntityWrittenContainerEvent;
use Shopware\Core\Framework\DataAbstractionLayer\Event\EntityWrittenEvent;
use Shopware\Core\Framework\Event\NestedEventCollection;
use Shopware\Core\Framework\Uuid\Uuid;

final class CacheInvalidationSubscriberTest extends TestCase
{
    private CacheInvalidator&MockObject $cacheInvalidator;

    private EventDispatcherInterface&MockObject $eventDispatcher;

    private EventFilterInterface&MockObject $eventFilter;

    private FilterFieldInformationCollectorInterface&MockObject $filterFieldInformationCollector;

    private FilterFieldInformationWithIdsCollectorInterface&MockObject $filterFieldInformationWithIdsCollector;

    private ListingFilterConfigurationRepositoryInterface&MockObject $listingFilterConfigurationRepository;

    private QueryExtenderInterface&MockObject $queryExtender;

    private QueryStarterInterface&MockObject $queryStarter;

    protected function setUp(): void
    {
        $this->eventFilter = $this->createMock(EventFilterInterface::class);
        $this->eventFilter->expects($this->never())
            ->method('filterEvents');

        $this->listingFilterConfigurationRepository = $this->createMock(ListingFilterConfigurationRepositoryInterface::class);
        $this->listingFilterConfigurationRepository->expects($this->never())
            ->method('getCheckboxListingFilterConfigurations');
        $this->listingFilterConfigurationRepository->expects($this->never())
            ->method('getMultiSelectListingFilterConfigurations');
        $this->listingFilterConfigurationRepository->expects($this->never())
            ->method('getRangeListingFilterConfigurations');
        $this->listingFilterConfigurationRepository->expects($this->never())
            ->method('getRangeIntervalListingFilterConfigurations');

        $this->filterFieldInformationCollector = $this->createMock(FilterFieldInformationCollectorInterface::class);
        $this->filterFieldInformationCollector->expects($this->never())
            ->method('collect');

        $this->filterFieldInformationWithIdsCollector = $this->createMock(FilterFieldInformationWithIdsCollectorInterface::class);
        $this->filterFieldInformationWithIdsCollector->expects($this->never())
            ->method('collect');

        $this->queryStarter = $this->createMock(QueryStarterInterface::class);
        $this->queryStarter->expects($this->never())
            ->method('startQuery');
        $this->queryExtender = $this->createMock(QueryExtenderInterface::class);
        $this->queryExtender->expects($this->never())
            ->method('extendQuery');

        $this->cacheInvalidator = $this->createMock(CacheInvalidator::class);
        $this->cacheInvalidator->expects($this->never())
            ->method('invalidate');

        $this->eventDispatcher = $this->createMock(EventDispatcherInterface::class);
        $this->eventDispatcher->expects($this->never())
            ->method('dispatch');
    }

    public static function invalidateWithFilteredOutEventsProvider(): \Generator
    {
        yield [self::createStub(NestedEventCollection::class)];
    }

    public function testInvalidate(): void
    {
        $entityWrittenEvent = self::createStub(EntityWrittenEvent::class);
        $filterFieldInformationCollection = new FilterFieldInformationCollection();
        $filterFieldInformationWithIdsCollection = new FilterFieldInformationWithIdsCollection();

        $event = $this->createMock(EntityWrittenContainerEvent::class);
        $event->expects($this->once())
            ->method('getEvents')
            ->willReturn($this->createStub(NestedEventCollection::class));

        $this->mockEventFilter($entityWrittenEvent);
        $this->mockListingFilterConfigurationRepository();
        $this->mockFilterFieldInformationCollector($filterFieldInformationCollection);
        $this->mockFilterFieldInformationWithIdsCollector($filterFieldInformationWithIdsCollection);

        $query = $this->createMock(QueryBuilder::class);
        $this->mockQueryStarter($query);
        $this->mockQueryExtender();

        $categoryId = Uuid::randomHex();
        $query->expects($this->once())
            ->method('fetchFirstColumn')
            ->willReturn([$categoryId]);

        $this->cacheInvalidator = $this->createMock(CacheInvalidator::class);
        $this->cacheInvalidator->expects($this->once())
            ->method('invalidate')
            ->willReturnCallback(function (array $tags) use ($categoryId): void {
                $this->assertCount(1, $tags);
                $this->assertSame(CachedProductListingRoute::buildName($categoryId), $tags[0]);
            });

        $subscriber = new CacheInvalidationSubscriber(
            $this->eventFilter,
            $this->listingFilterConfigurationRepository,
            $this->filterFieldInformationCollector,
            $this->filterFieldInformationWithIdsCollector,
            $this->queryStarter,
            $this->queryExtender,
            $this->cacheInvalidator,
            $this->eventDispatcher
        );

        $subscriber->invalidate($event);
    }

    public function testInvalidateWithEmptyCategoryIdsList(): void
    {
        $entityWrittenEvent = self::createStub(EntityWrittenEvent::class);
        $filterFieldInformationCollection = new FilterFieldInformationCollection();
        $filterFieldInformationWithIdsCollection = new FilterFieldInformationWithIdsCollection();

        $event = $this->createMock(EntityWrittenContainerEvent::class);
        $event->expects($this->once())
            ->method('getEvents')
            ->willReturn($this->createStub(NestedEventCollection::class));

        $this->mockEventFilter($entityWrittenEvent);
        $this->mockListingFilterConfigurationRepository();
        $this->mockFilterFieldInformationCollector($filterFieldInformationCollection);
        $this->mockFilterFieldInformationWithIdsCollector($filterFieldInformationWithIdsCollection);

        $query = $this->createMock(QueryBuilder::class);
        $this->mockQueryStarter($query);
        $this->mockQueryExtender();

        $query->expects($this->once())
            ->method('fetchFirstColumn')
            ->willReturn([]);

        $subscriber = new CacheInvalidationSubscriber(
            $this->eventFilter,
            $this->listingFilterConfigurationRepository,
            $this->filterFieldInformationCollector,
            $this->filterFieldInformationWithIdsCollector,
            $this->queryStarter,
            $this->queryExtender,
            $this->cacheInvalidator,
            $this->eventDispatcher
        );

        $subscriber->invalidate($event);
    }

    public function testInvalidateWithFailedQueryBuilding(): void
    {
        $entityWrittenEvent = self::createStub(EntityWrittenEvent::class);
        $filterFieldInformationCollection = new FilterFieldInformationCollection();
        $filterFieldInformationWithIdsCollection = new FilterFieldInformationWithIdsCollection();

        $event = $this->createMock(EntityWrittenContainerEvent::class);
        $event->expects($this->once())
            ->method('getEvents')
            ->willReturn($this->createStub(NestedEventCollection::class));

        $this->mockEventFilter($entityWrittenEvent);
        $this->mockListingFilterConfigurationRepository();
        $this->mockFilterFieldInformationCollector($filterFieldInformationCollection);

        $this->filterFieldInformationWithIdsCollector = $this->createMock(FilterFieldInformationWithIdsCollectorInterface::class);
        $this->filterFieldInformationWithIdsCollector->expects($this->once())
            ->method('collect')
            ->willReturnCallback(
                function (
                    EntityWrittenContainerEvent $eventArgument,
                    FilterFieldInformationCollection $filterFieldInformationCollectionArgument
                ) use (
                    $event,
                    $filterFieldInformationCollection,
                    $filterFieldInformationWithIdsCollection
                ): FilterFieldInformationWithIdsCollection {
                    $this->assertSame($event, $eventArgument);
                    $this->assertSame($filterFieldInformationCollection, $filterFieldInformationCollectionArgument);

                    return $filterFieldInformationWithIdsCollection;
                }
            );

        $query = $this->createMock(QueryBuilder::class);
        $this->mockQueryStarter($query);

        $this->queryExtender = $this->createMock(QueryExtenderInterface::class);
        $this->queryExtender->expects($this->once())
            ->method('extendQuery')
            ->with($query, $filterFieldInformationWithIdsCollection, $entityWrittenEvent->getContext())
            ->willThrowException(new \RuntimeException('Test Exception'));

        $this->eventDispatcher = $this->createMock(EventDispatcherInterface::class);
        $this->eventDispatcher->expects($this->once())
            ->method('dispatch')
            ->willReturnCallback(function ($eventArgument) use ($event, $filterFieldInformationWithIdsCollection): void {
                $this->assertInstanceOf(CacheInvalidationCategoryQueryBuildingFailedEvent::class, $eventArgument);
                $this->assertSame($filterFieldInformationWithIdsCollection, $eventArgument->getFilterFieldInformationWithIds());
                $this->assertInstanceOf(\RuntimeException::class, $eventArgument->getException());
                $this->assertSame($event, $eventArgument->getEvent());
            });

        $subscriber = new CacheInvalidationSubscriber(
            $this->eventFilter,
            $this->listingFilterConfigurationRepository,
            $this->filterFieldInformationCollector,
            $this->filterFieldInformationWithIdsCollector,
            $this->queryStarter,
            $this->queryExtender,
            $this->cacheInvalidator,
            $this->eventDispatcher
        );

        $subscriber->invalidate($event);
    }

    public function testInvalidateWithFailedQueryExecution(): void
    {
        $entityWrittenEvent = self::createStub(EntityWrittenEvent::class);
        $filterFieldInformationCollection = new FilterFieldInformationCollection();
        $filterFieldInformationWithIdsCollection = new FilterFieldInformationWithIdsCollection();

        $event = $this->createMock(EntityWrittenContainerEvent::class);
        $event->expects($this->once())
            ->method('getEvents')
            ->willReturn($this->createStub(NestedEventCollection::class));

        $this->mockEventFilter($entityWrittenEvent);
        $this->mockListingFilterConfigurationRepository();
        $this->mockFilterFieldInformationCollector($filterFieldInformationCollection);
        $this->mockFilterFieldInformationWithIdsCollector($filterFieldInformationWithIdsCollection);

        $query = $this->createMock(QueryBuilder::class);
        $this->mockQueryStarter($query);

        $this->queryExtender = $this->createMock(QueryExtenderInterface::class);
        $this->queryExtender->expects($this->once())
            ->method('extendQuery')
            ->with($query, $filterFieldInformationWithIdsCollection, $entityWrittenEvent->getContext());

        $query->expects($this->once())
            ->method('fetchFirstColumn')
            ->willThrowException(new \RuntimeException('Test Exception'));

        $this->eventDispatcher = $this->createMock(EventDispatcherInterface::class);
        $this->eventDispatcher->expects($this->once())
            ->method('dispatch')
            ->willReturnCallback(function ($eventArgument) use ($event, $query): void {
                $this->assertInstanceOf(CacheInvalidationCategoryQueryExecutionFailedEvent::class, $eventArgument);
                $this->assertSame($query, $eventArgument->getQueryBuilder());
                $this->assertInstanceOf(\RuntimeException::class, $eventArgument->getException());
                $this->assertSame($event, $eventArgument->getEvent());
            });

        $subscriber = new CacheInvalidationSubscriber(
            $this->eventFilter,
            $this->listingFilterConfigurationRepository,
            $this->filterFieldInformationCollector,
            $this->filterFieldInformationWithIdsCollector,
            $this->queryStarter,
            $this->queryExtender,
            $this->cacheInvalidator,
            $this->eventDispatcher
        );

        $subscriber->invalidate($event);
    }

    #[DataProvider('invalidateWithFilteredOutEventsProvider')]
    public function testInvalidateWithFilteredOutEvents(NestedEventCollection $nestedEvent): void
    {
        $this->eventFilter = $this->createMock(EventFilterInterface::class);
        $this->eventFilter->expects($this->once())
            ->method('filterEvents')
            ->willReturn([]);

        $subscriber = new CacheInvalidationSubscriber(
            $this->eventFilter,
            $this->listingFilterConfigurationRepository,
            $this->filterFieldInformationCollector,
            $this->filterFieldInformationWithIdsCollector,
            $this->queryStarter,
            $this->queryExtender,
            $this->cacheInvalidator,
            $this->eventDispatcher
        );

        $event = $this->createMock(EntityWrittenContainerEvent::class);
        $event->expects($this->once())
            ->method('getEvents')
            ->willReturn($nestedEvent);

        $subscriber->invalidate($event);
    }

    public function testInvalidateWithoutEvents(): void
    {
        $subscriber = new CacheInvalidationSubscriber(
            $this->eventFilter,
            $this->listingFilterConfigurationRepository,
            $this->filterFieldInformationCollector,
            $this->filterFieldInformationWithIdsCollector,
            $this->queryStarter,
            $this->queryExtender,
            $this->cacheInvalidator,
            $this->eventDispatcher
        );

        $event = $this->createMock(EntityWrittenContainerEvent::class);
        $event->expects($this->once())
            ->method('getEvents')
            ->willReturn(null);

        $subscriber->invalidate($event);
    }

    private function mockEventFilter(EntityWrittenEvent $entityWrittenEvent): void
    {
        $this->eventFilter = $this->createMock(EventFilterInterface::class);
        $this->eventFilter->expects($this->once())
            ->method('filterEvents')
            ->willReturn([$entityWrittenEvent]);
    }

    private function mockFilterFieldInformationCollector(FilterFieldInformationCollection $filterFieldInformationCollection): void
    {
        $this->filterFieldInformationCollector = $this->createMock(FilterFieldInformationCollectorInterface::class);
        $this->filterFieldInformationCollector->expects($this->once())
            ->method('collect')
            ->willReturn($filterFieldInformationCollection);
    }

    private function mockFilterFieldInformationWithIdsCollector(
        FilterFieldInformationWithIdsCollection $filterFieldInformationWithIdsCollection
    ): void {
        $this->filterFieldInformationWithIdsCollector = $this->createMock(FilterFieldInformationWithIdsCollectorInterface::class);
        $this->filterFieldInformationWithIdsCollector->expects($this->once())
            ->method('collect')
            ->willReturn($filterFieldInformationWithIdsCollection);
    }

    private function mockListingFilterConfigurationRepository(): void
    {
        $this->listingFilterConfigurationRepository = $this->createMock(ListingFilterConfigurationRepositoryInterface::class);
        $this->listingFilterConfigurationRepository->expects($this->once())
            ->method('getCheckboxListingFilterConfigurations')
            ->willReturn(new CheckboxListingFilterConfigurationCollection());
        $this->listingFilterConfigurationRepository->expects($this->once())
            ->method('getMultiSelectListingFilterConfigurations')
            ->willReturn(new MultiSelectListingFilterConfigurationCollection());
        $this->listingFilterConfigurationRepository->expects($this->once())
            ->method('getRangeListingFilterConfigurations')
            ->willReturn(new RangeListingFilterConfigurationCollection());
        $this->listingFilterConfigurationRepository->expects($this->once())
            ->method('getRangeIntervalListingFilterConfigurations')
            ->willReturn(new RangeIntervalListingFilterConfigurationCollection());
    }

    private function mockQueryExtender(): void
    {
        $this->queryExtender = $this->createMock(QueryExtenderInterface::class);
        $this->queryExtender->expects($this->once())
            ->method('extendQuery');
    }

    private function mockQueryStarter(QueryBuilder $query): void
    {
        $this->queryStarter = $this->createMock(QueryStarterInterface::class);
        $this->queryStarter->expects($this->once())
            ->method('startQuery')
            ->willReturn($query);
    }
}
