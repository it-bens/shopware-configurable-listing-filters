<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Test\PHPUnit\Unit\Core\Framework\Adapter\Cache\CacheInvalidation;

use ITB\ITBConfigurableListingFilters\Core\Framework\Adapter\Cache\CacheInvalidation\EventFilter;
use ITB\ITBConfigurableListingFilters\Core\Framework\Adapter\Cache\CacheInvalidation\EventFlattenerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Shopware\Core\Content\Product\ProductDefinition;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\Event\EntityWrittenContainerEvent;
use Shopware\Core\Framework\DataAbstractionLayer\Event\EntityWrittenEvent;
use Shopware\Core\Framework\MessageQueue\ScheduledTask\ScheduledTaskDefinition;
use Shopware\Core\Framework\Plugin\Aggregate\PluginTranslation\PluginTranslationDefinition;
use Shopware\Core\Framework\Plugin\PluginDefinition;

#[CoversClass(EventFilter::class)]
final class EventFilterTest extends TestCase
{
    public static function filterEventsProvider(): \Generator
    {
        $context = self::createStub(Context::class);

        // Events to be kept
        $productEvent = new EntityWrittenEvent(ProductDefinition::ENTITY_NAME, [], $context);
        $categoryEvent = new EntityWrittenEvent('category', [], $context);

        // Events to be filtered out
        $scheduledTaskEvent = new EntityWrittenEvent(ScheduledTaskDefinition::ENTITY_NAME, [], $context);
        $pluginEvent = new EntityWrittenEvent(PluginDefinition::ENTITY_NAME, [], $context);
        $pluginTranslationEvent = new EntityWrittenEvent(PluginTranslationDefinition::ENTITY_NAME, [], $context);

        // All flattened events for different scenarios
        $allEventsMixed = [$productEvent, $scheduledTaskEvent, $categoryEvent, $pluginEvent, $pluginTranslationEvent];
        $allEventsToKeep = [$productEvent, $categoryEvent];
        $allEventsToFilter = [$scheduledTaskEvent, $pluginEvent, $pluginTranslationEvent];
        $noEvents = [];

        $containerMixed = self::createStub(EntityWrittenContainerEvent::class);
        $containerKeep = self::createStub(EntityWrittenContainerEvent::class);
        $containerFilter = self::createStub(EntityWrittenContainerEvent::class);
        $containerEmpty = self::createStub(EntityWrittenContainerEvent::class);

        yield 'mixed events' => [
            'containerEvent' => $containerMixed,
            'flattenedEvents' => $allEventsMixed,
            'expectedFilteredEvents' => [$productEvent, $categoryEvent], // Only keep product and category
        ];

        yield 'only keep events' => [
            'containerEvent' => $containerKeep,
            'flattenedEvents' => $allEventsToKeep,
            'expectedFilteredEvents' => $allEventsToKeep, // Keep all
        ];

        yield 'only filter events' => [
            'containerEvent' => $containerFilter,
            'flattenedEvents' => $allEventsToFilter,
            'expectedFilteredEvents' => [], // Filter all
        ];

        yield 'no events flattened' => [
            'containerEvent' => $containerEmpty,
            'flattenedEvents' => $noEvents,
            'expectedFilteredEvents' => [], // Still empty
        ];
    }

    /**
     * @param array<int, EntityWrittenEvent> $flattenedEvents
     * @param array<int, EntityWrittenEvent> $expectedFilteredEvents
     */
    #[DataProvider('filterEventsProvider')]
    public function testFilterEvents(
        EntityWrittenContainerEvent $containerEvent,
        array $flattenedEvents,
        array $expectedFilteredEvents
    ): void {
        $eventFlattener = $this->createMock(EventFlattenerInterface::class);

        $eventFlattener->expects($this->once())
            ->method('flattenEvents')
            ->willReturnCallback(
                function (EntityWrittenContainerEvent $actualContainerEvent, array &$actualEvents) use (
                    $containerEvent,
                    $flattenedEvents
                ): void {
                    $this->assertSame($containerEvent, $actualContainerEvent);
                    $this->assertEmpty($actualEvents);

                    $actualEvents = $flattenedEvents;
                }
            );

        $eventFilter = new EventFilter($eventFlattener);
        $actualFilteredEvents = $eventFilter->filterEvents($containerEvent);

        $this->assertEquals(array_values($expectedFilteredEvents), array_values($actualFilteredEvents));
    }
}
