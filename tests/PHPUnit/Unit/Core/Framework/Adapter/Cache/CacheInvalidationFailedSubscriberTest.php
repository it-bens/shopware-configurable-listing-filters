<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Test\PHPUnit\Unit\Core\Framework\Adapter\Cache;

use ITB\ITBConfigurableListingFilters\Core\Framework\Adapter\Cache\CacheInvalidation\FilterFieldInformationWithIds\FilterFieldInformationWithIdsCollection;
use ITB\ITBConfigurableListingFilters\Core\Framework\Adapter\Cache\CacheInvalidationFailed\CacheInvalidationCategoryQueryBuildingFailedEvent;
use ITB\ITBConfigurableListingFilters\Core\Framework\Adapter\Cache\CacheInvalidationFailed\CacheInvalidationCategoryQueryExecutionFailedEvent;
use ITB\ITBConfigurableListingFilters\Core\Framework\Adapter\Cache\CacheInvalidationFailedSubscriber;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\Dbal\QueryBuilder;
use Shopware\Core\Framework\DataAbstractionLayer\Event\EntityWrittenContainerEvent;

#[CoversClass(CacheInvalidationFailedSubscriber::class)]
final class CacheInvalidationFailedSubscriberTest extends TestCase
{
    public static function provideCacheInvalidationCategoryQueryBuildingFailedData(): \Generator
    {
        $exception = new \RuntimeException('Test Exception Building');
        $filterInfoCollectionMock = new FilterFieldInformationWithIdsCollection();
        $entityWrittenEventMock = self::createStub(EntityWrittenContainerEvent::class);
        $contextMock = self::createStub(Context::class);

        $event = new CacheInvalidationCategoryQueryBuildingFailedEvent(
            $filterInfoCollectionMock,
            $exception,
            $entityWrittenEventMock,
            $contextMock
        );

        $expectedLogMessage = 'Query building failed for cache invalidation failed';
        $expectedLogContext = [
            'message' => $exception->getMessage(),
            'filterFieldInformationWithIds' => $filterInfoCollectionMock,
            'exception' => $exception,
            'entityWrittenEvent' => $entityWrittenEventMock,
            'context' => $contextMock,
        ];

        yield [$event, $expectedLogMessage, $expectedLogContext];
    }

    public static function provideCacheInvalidationCategoryQueryExecutionFailedData(): \Generator
    {
        $exception = new \RuntimeException('Test Exception Execution');
        $queryBuilderMock = self::createStub(QueryBuilder::class);
        $entityWrittenEventMock = self::createStub(EntityWrittenContainerEvent::class);
        $contextMock = self::createStub(Context::class);

        $event = new CacheInvalidationCategoryQueryExecutionFailedEvent(
            $queryBuilderMock,
            $exception,
            $entityWrittenEventMock,
            $contextMock
        );

        $expectedLogMessage = 'Query execution failed for cache invalidation failed';
        $expectedLogContext = [
            'message' => $exception->getMessage(),
            'query' => $queryBuilderMock,
            'exception' => $exception,
            'entityWrittenEvent' => $entityWrittenEventMock,
            'context' => $contextMock,
        ];

        yield [$event, $expectedLogMessage, $expectedLogContext];
    }

    public function testGetSubscribedEvents(): void
    {
        $expectedEvents = [
            CacheInvalidationCategoryQueryBuildingFailedEvent::class => 'onCacheInvalidationCategoryQueryBuildingFailed',
            CacheInvalidationCategoryQueryExecutionFailedEvent::class => 'onCacheInvalidationCategoryQueryExecutionFailedEvent',
        ];

        $this->assertSame($expectedEvents, CacheInvalidationFailedSubscriber::getSubscribedEvents());
    }

    /**
     * @param array<string, mixed> $expectedLogContext
     */
    #[DataProvider('provideCacheInvalidationCategoryQueryBuildingFailedData')]
    public function testOnCacheInvalidationCategoryQueryBuildingFailed(
        CacheInvalidationCategoryQueryBuildingFailedEvent $event,
        string $expectedLogMessage,
        array $expectedLogContext
    ): void {
        $loggerMock = $this->createMock(LoggerInterface::class);
        $subscriber = new CacheInvalidationFailedSubscriber($loggerMock);

        $loggerMock->expects($this->once())
            ->method('error')
            ->with($expectedLogMessage, $expectedLogContext);

        $subscriber->onCacheInvalidationCategoryQueryBuildingFailed($event);
    }

    /**
     * @param array<string, mixed> $expectedLogContext
     */
    #[DataProvider('provideCacheInvalidationCategoryQueryExecutionFailedData')]
    public function testOnCacheInvalidationCategoryQueryExecutionFailedEvent(
        CacheInvalidationCategoryQueryExecutionFailedEvent $event,
        string $expectedLogMessage,
        array $expectedLogContext
    ): void {
        $loggerMock = $this->createMock(LoggerInterface::class);
        $subscriber = new CacheInvalidationFailedSubscriber($loggerMock);

        $loggerMock->expects($this->once())
            ->method('error')
            ->with($expectedLogMessage, $expectedLogContext);

        $subscriber->onCacheInvalidationCategoryQueryExecutionFailedEvent($event);
    }
}
