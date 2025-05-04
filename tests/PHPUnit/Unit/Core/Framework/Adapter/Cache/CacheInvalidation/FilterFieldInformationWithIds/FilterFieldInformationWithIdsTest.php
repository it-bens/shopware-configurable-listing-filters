<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Test\PHPUnit\Unit\Core\Framework\Adapter\Cache\CacheInvalidation\FilterFieldInformationWithIds;

use ITB\ITBConfigurableListingFilters\Core\Framework\Adapter\Cache\CacheInvalidation\FilterFieldInformationWithIds\FilterFieldInformationWithIds;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;
use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;

#[CoversClass(FilterFieldInformationWithIds::class)]
final class FilterFieldInformationWithIdsTest extends TestCase
{
    public static function fullyQualifiedTargetDefinitionIdFieldProvider(): \Generator
    {
        /** @var Stub&EntityDefinition $entityDefinition */
        $entityDefinition = self::createStub(EntityDefinition::class);

        yield 'empty path' => [new FilterFieldInformationWithIds('', $entityDefinition, ['123']), '.id'];

        yield 'non-empty path' => [new FilterFieldInformationWithIds('product', $entityDefinition, ['123']), 'product.id'];
    }

    #[DataProvider('fullyQualifiedTargetDefinitionIdFieldProvider')]
    public function testFullyQualifiedTargetDefinitionIdField(
        FilterFieldInformationWithIds $filterFieldInformationWithIds,
        string $expectedFullyQualifiedTargetDefinitionIdField
    ): void {
        $this->assertSame(
            $expectedFullyQualifiedTargetDefinitionIdField,
            $filterFieldInformationWithIds->fullyQualifiedTargetDefinitionIdField()
        );
    }
}
