<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Test\PHPUnit\Unit\Core\Framework\Adapter\Cache\CacheInvalidation;

use ITB\ITBConfigurableListingFilters\Core\Framework\Adapter\Cache\CacheInvalidation\EventFlattener;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\Event\EntityWrittenContainerEvent;
use Shopware\Core\Framework\DataAbstractionLayer\Event\EntityWrittenEvent;
use Shopware\Core\Framework\Event\NestedEventCollection;

#[CoversClass(EventFlattener::class)]
final class EventFlattenerTest extends TestCase
{
    public static function flattenEventsProvider(): \Generator
    {
        $context = self::createStub(Context::class);

        $event1 = new EntityWrittenEvent('entity1', [], $context);
        $event2 = new EntityWrittenEvent('entity2', [], $context);
        $event3 = new EntityWrittenEvent('entity3', [], $context);
        $event4 = new EntityWrittenEvent('entity4', [], $context);

        $nestedContainerEvent1 = self::createStub(EntityWrittenContainerEvent::class);
        $nestedContainerEvent1->method('getEvents')
            ->willReturn(new NestedEventCollection([$event3]));

        $nestedContainerEvent2 = self::createStub(EntityWrittenContainerEvent::class);
        $nestedContainerEvent2->method('getEvents')
            ->willReturn(new NestedEventCollection([$event4]));

        $subContainerEvent = self::createStub(EntityWrittenContainerEvent::class);
        $subContainerEvent->method('getEvents')
            ->willReturn(new NestedEventCollection([$nestedContainerEvent1, $nestedContainerEvent2]));

        $mainContainerEventNested = self::createStub(EntityWrittenContainerEvent::class);
        $mainContainerEventNested->method('getEvents')
            ->willReturn(new NestedEventCollection([$event1, $subContainerEvent, $event2]));

        yield 'nested events' => [
            'mainEvent' => $mainContainerEventNested,
            'expectedEvents' => [$event1, $event3, $event4, $event2],
        ];

        $mainContainerEventEmptyTop = self::createStub(EntityWrittenContainerEvent::class);
        $mainContainerEventEmptyTop->method('getEvents')
            ->willReturn(new NestedEventCollection([]));

        yield 'empty top level' => [
            'mainEvent' => $mainContainerEventEmptyTop,
            'expectedEvents' => [],
        ];

        $event5 = new EntityWrittenEvent('entity5', [], $context);
        $emptyNestedContainer = self::createStub(EntityWrittenContainerEvent::class);
        $emptyNestedContainer->method('getEvents')
            ->willReturn(new NestedEventCollection([]));

        $mainContainerEventEmptyNested = self::createStub(EntityWrittenContainerEvent::class);
        $mainContainerEventEmptyNested->method('getEvents')
            ->willReturn(new NestedEventCollection([$event5, $emptyNestedContainer]));

        yield 'empty nested level' => [
            'mainEvent' => $mainContainerEventEmptyNested,
            'expectedEvents' => [$event5],
        ];

        $mainContainerEventNull = self::createStub(EntityWrittenContainerEvent::class);
        $mainContainerEventNull->method('getEvents')
            ->willReturn(null);

        yield 'null events' => [
            'mainEvent' => $mainContainerEventNull,
            'expectedEvents' => [],
        ];

        $event6 = new EntityWrittenEvent('entity6', [], $context);
        $event7 = new EntityWrittenEvent('entity7', [], $context);
        $mainContainerNoNesting = self::createStub(EntityWrittenContainerEvent::class);
        $mainContainerNoNesting->method('getEvents')
            ->willReturn(new NestedEventCollection([$event6, $event7]));

        yield 'no nesting' => [
            'mainEvent' => $mainContainerNoNesting,
            'expectedEvents' => [$event6, $event7],
        ];
    }

    /**
     * @param array<int, EntityWrittenEvent> $expectedEvents
     */
    #[DataProvider('flattenEventsProvider')]
    public function testFlattenEvents(EntityWrittenContainerEvent $mainEvent, array $expectedEvents): void
    {
        $flattener = new EventFlattener();
        $flattenedEvents = [];
        $flattener->flattenEvents($mainEvent, $flattenedEvents);

        $this->assertCount(count($expectedEvents), $flattenedEvents);
        foreach ($expectedEvents as $index => $expectedEvent) {
            $this->assertSame($expectedEvent, $flattenedEvents[$index]);
        }
    }
}
