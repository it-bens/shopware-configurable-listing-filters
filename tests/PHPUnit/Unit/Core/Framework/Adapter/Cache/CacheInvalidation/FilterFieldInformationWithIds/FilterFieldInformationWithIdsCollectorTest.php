<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Test\PHPUnit\Unit\Core\Framework\Adapter\Cache\CacheInvalidation\FilterFieldInformationWithIds;

use ITB\ITBConfigurableListingFilters\Core\Framework\Adapter\Cache\CacheInvalidation\FilterFieldInformation\FilterFieldInformation;
use ITB\ITBConfigurableListingFilters\Core\Framework\Adapter\Cache\CacheInvalidation\FilterFieldInformation\FilterFieldInformationCollection;
use ITB\ITBConfigurableListingFilters\Core\Framework\Adapter\Cache\CacheInvalidation\FilterFieldInformationWithIds\FilterFieldInformationWithIds;
use ITB\ITBConfigurableListingFilters\Core\Framework\Adapter\Cache\CacheInvalidation\FilterFieldInformationWithIds\FilterFieldInformationWithIdsBuilder\ForNonTranslatedDefinitionInterface as BuilderForNonTranslatedDefinitionInterface;
use ITB\ITBConfigurableListingFilters\Core\Framework\Adapter\Cache\CacheInvalidation\FilterFieldInformationWithIds\FilterFieldInformationWithIdsBuilder\ForTranslatedDefinitionInterface as BuilderForTranslatedDefinitionInterface;
use ITB\ITBConfigurableListingFilters\Core\Framework\Adapter\Cache\CacheInvalidation\FilterFieldInformationWithIds\FilterFieldInformationWithIdsCollector;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityTranslationDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Event\EntityWrittenContainerEvent;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;

/**
 * @phpstan-import-type IdsFromGetPrimaryKeys from FilterFieldInformationWithIdsCollector
 */
#[CoversClass(FilterFieldInformationWithIdsCollector::class)]
final class FilterFieldInformationWithIdsCollectorTest extends TestCase
{
    public static function collectProvider(): \Generator
    {
        $targetEntityDefinition = self::createStub(EntityDefinition::class);
        $targetEntityDefinition->method('getEntityName')
            ->willReturn('product_manufacturer');
        $field = self::createStub(StringField::class);
        $field->method('getPropertyName')
            ->willReturn('non_translated');

        $filterFieldInformation = new FilterFieldInformation('product.manufacturer.non_translated', $targetEntityDefinition, $field);
        $filterFieldInformationCollection = new FilterFieldInformationCollection();
        $filterFieldInformationCollection->add($filterFieldInformation);

        $expectedIds = [
            [
                'id' => 'uuid-1',
            ],
            [
                'id' => 'uuid-2',
            ],
        ];

        yield 'with non translated field' => [
            $filterFieldInformationCollection,
            $targetEntityDefinition,
            'non_translated',
            0,
            1,
            'product.manufacturer',
            $expectedIds,
            ['uuid-1', 'uuid-2'],
            1,
        ];

        $targetEntityDefinition = self::createStub(EntityTranslationDefinition::class);
        $targetEntityDefinition->method('getEntityName')
            ->willReturn('product_manufacturer');
        $field = self::createStub(StringField::class);
        $field->method('getPropertyName')
            ->willReturn('translated');

        $filterFieldInformation = new FilterFieldInformation('product.manufacturer.translated', $targetEntityDefinition, $field);
        $filterFieldInformationCollection = new FilterFieldInformationCollection();
        $filterFieldInformationCollection->add($filterFieldInformation);

        yield 'with translated field' => [
            $filterFieldInformationCollection,
            $targetEntityDefinition,
            'translated',
            1,
            0,
            'product.manufacturer',
            $expectedIds,
            ['uuid-1', 'uuid-2'],
            1,
        ];

        $expectedIds = [];

        yield 'without ids in event' => [
            $filterFieldInformationCollection,
            $targetEntityDefinition,
            'translated',
            0,
            0,
            'product.manufacturer',
            $expectedIds,
            [],
            0,
        ];
    }

    /**
     * @param IdsFromGetPrimaryKeys $expectedIds
     * @param array<int, string> $idsForFilterFieldInformationWithIds
     */
    #[DataProvider('collectProvider')]
    public function testCollect(
        FilterFieldInformationCollection $filterFieldInformationCollection,
        EntityDefinition $expectedTargetEntityDefinition,
        string $expectedPropertyName,
        int $expectedBuilderForTranslatedDefinitionCallCount,
        int $expectedBuilderForNonTranslatedDefinitionCallCount,
        string $expectedPathToTargetEntity,
        array $expectedIds,
        array $idsForFilterFieldInformationWithIds,
        int $expectedFilterFieldInformationWithIdsInCollectionCount
    ): void {
        $event = $this->createMock(EntityWrittenContainerEvent::class);
        $event->expects($this->once())
            ->method('getPrimaryKeysWithPropertyChange')
            ->willReturnCallback(function (string $entityArgument, array $propertiesArgument) use (
                $expectedTargetEntityDefinition,
                $expectedPropertyName,
                $expectedIds
            ): array {
                $this->assertSame($expectedTargetEntityDefinition->getEntityName(), $entityArgument);
                $this->assertSame([$expectedPropertyName], $propertiesArgument);

                return $expectedIds;
            });

        $filterFieldInformationWithIds = new FilterFieldInformationWithIds(
            $expectedPathToTargetEntity,
            $expectedTargetEntityDefinition,
            $idsForFilterFieldInformationWithIds
        );

        $builderForTranslatedDefinition = $this->createMock(BuilderForTranslatedDefinitionInterface::class);
        $builderForTranslatedDefinition->expects($this->exactly($expectedBuilderForTranslatedDefinitionCallCount))
            ->method('build')
            ->willReturnCallback(
                function (EntityDefinition $targetEntityDefinitionArgument, string $pathToTargetEntityArgument, array $idsArgument) use (
                    $expectedTargetEntityDefinition,
                    $expectedPathToTargetEntity,
                    $expectedIds,
                    $filterFieldInformationWithIds
                ): FilterFieldInformationWithIds {
                    $this->assertSame($expectedTargetEntityDefinition, $targetEntityDefinitionArgument);
                    $this->assertSame($expectedPathToTargetEntity, $pathToTargetEntityArgument);
                    $this->assertSame($expectedIds, $idsArgument);

                    return $filterFieldInformationWithIds;
                }
            );

        $builderForNonTranslatedDefinition = $this->createMock(BuilderForNonTranslatedDefinitionInterface::class);
        $builderForNonTranslatedDefinition->expects($this->exactly($expectedBuilderForNonTranslatedDefinitionCallCount))
            ->method('build')
            ->willReturnCallback(
                function (EntityDefinition $targetEntityDefinitionArgument, string $pathToTargetEntityArgument, array $idsArgument) use (
                    $expectedTargetEntityDefinition,
                    $expectedPathToTargetEntity,
                    $expectedIds,
                    $filterFieldInformationWithIds
                ): FilterFieldInformationWithIds {
                    $this->assertSame($expectedTargetEntityDefinition, $targetEntityDefinitionArgument);
                    $this->assertSame($expectedPathToTargetEntity, $pathToTargetEntityArgument);
                    $this->assertSame($expectedIds, $idsArgument);

                    return $filterFieldInformationWithIds;
                }
            );

        $filterFieldInformationWithIdsCollector = new FilterFieldInformationWithIdsCollector(
            $builderForTranslatedDefinition,
            $builderForNonTranslatedDefinition
        );

        $filterFieldInformationWithIdsCollection = $filterFieldInformationWithIdsCollector->collect(
            $event,
            $filterFieldInformationCollection
        );
        $this->assertCount(
            $expectedFilterFieldInformationWithIdsInCollectionCount,
            $filterFieldInformationWithIdsCollection->allWithoutFilterFieldInformationsWithoutIds()
        );
        if ($expectedFilterFieldInformationWithIdsInCollectionCount !== 0) {
            $this->assertSame(
                $filterFieldInformationWithIds,
                $filterFieldInformationWithIdsCollection->allWithoutFilterFieldInformationsWithoutIds()[0]
            );
        }
    }
}
