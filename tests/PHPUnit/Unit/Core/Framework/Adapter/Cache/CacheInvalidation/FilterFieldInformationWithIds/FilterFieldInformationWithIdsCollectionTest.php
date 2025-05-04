<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Test\PHPUnit\Unit\Core\Framework\Adapter\Cache\CacheInvalidation\FilterFieldInformationWithIds;

use ITB\ITBConfigurableListingFilters\Core\Framework\Adapter\Cache\CacheInvalidation\FilterFieldInformationWithIds\FilterFieldInformationWithIds;
use ITB\ITBConfigurableListingFilters\Core\Framework\Adapter\Cache\CacheInvalidation\FilterFieldInformationWithIds\FilterFieldInformationWithIdsCollection;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;
use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;

#[CoversClass(FilterFieldInformationWithIdsCollection::class)]
final class FilterFieldInformationWithIdsCollectionTest extends TestCase
{
    public static function addAndAllWithoutFilterFieldInformationsWithoutIdsProvider(): \Generator
    {
        /** @var Stub&EntityDefinition $entityDefinition */
        $entityDefinition = self::createStub(EntityDefinition::class);

        yield 'filter field information with non empty id list' => [
            new FilterFieldInformationWithIdsCollection(),
            new FilterFieldInformationWithIds('entity1.field1_prop', $entityDefinition, ['id1']),
            1,
        ];

        yield 'filter field information with empty id list' => [
            new FilterFieldInformationWithIdsCollection(),
            new FilterFieldInformationWithIds('entity1.field1_prop', $entityDefinition, []),
            0,
        ];
    }

    #[DataProvider('addAndAllWithoutFilterFieldInformationsWithoutIdsProvider')]
    public function testAddAndAllWithoutFilterFieldInformationsWithoutIds(
        FilterFieldInformationWithIdsCollection $filterFieldInformationWithIdsCollection,
        FilterFieldInformationWithIds $filterFieldInformationWithIds,
        int $expectedFilterFieldInformationWithIdsCount
    ): void {
        $filterFieldInformationWithIdsCollection->add($filterFieldInformationWithIds);

        $this->assertCount(
            $expectedFilterFieldInformationWithIdsCount,
            $filterFieldInformationWithIdsCollection->allWithoutFilterFieldInformationsWithoutIds()
        );
        if ($expectedFilterFieldInformationWithIdsCount !== 0) {
            $this->assertSame(
                $filterFieldInformationWithIds,
                $filterFieldInformationWithIdsCollection->allWithoutFilterFieldInformationsWithoutIds()[0]
            );
        }
    }
}
