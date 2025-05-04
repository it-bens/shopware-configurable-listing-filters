<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Test\PHPUnit\Unit\Core\Framework\Adapter\Cache\CacheInvalidation;

use Doctrine\DBAL\ArrayParameterType;
use Doctrine\DBAL\Query\Expression\CompositeExpression;
use Doctrine\DBAL\Query\Expression\ExpressionBuilder;
use ITB\ITBConfigurableListingFilters\Core\Framework\Adapter\Cache\CacheInvalidation\FilterFieldInformationWithIds\FilterFieldInformationWithIds;
use ITB\ITBConfigurableListingFilters\Core\Framework\Adapter\Cache\CacheInvalidation\FilterFieldInformationWithIds\FilterFieldInformationWithIdsCollection;
use ITB\ITBConfigurableListingFilters\Core\Framework\Adapter\Cache\CacheInvalidation\QueryExtender;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Shopware\Core\Content\Product\Aggregate\ProductManufacturer\ProductManufacturerDefinition;
use Shopware\Core\Content\Product\ProductDefinition;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\Dbal\EntityDefinitionQueryHelper;
use Shopware\Core\Framework\DataAbstractionLayer\Dbal\QueryBuilder;
use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\Uuid\Uuid;

#[CoversClass(QueryExtender::class)]
final class QueryExtenderTest extends TestCase
{
    public static function extendQueryProvider(): \Generator
    {
        $expectedIds = [Uuid::randomHex(), Uuid::randomHex()];

        $productDefinition = self::createStub(ProductDefinition::class);
        $manufacturerDefinition = self::createStub(ProductManufacturerDefinition::class);
        $manufacturerDefinition->method('getEntityName')
            ->willReturn(ProductManufacturerDefinition::ENTITY_NAME);
        $context = self::createStub(Context::class);

        $filterFieldInformationWithIds = new FilterFieldInformationWithIds('product.manufacturer', $manufacturerDefinition, $expectedIds);
        $filterFieldInformationWithIdsCollection = new FilterFieldInformationWithIdsCollection();
        $filterFieldInformationWithIdsCollection->add($filterFieldInformationWithIds);

        $expectedFullyQualifiedTargetDefinitionIdField = 'product.manufacturer.id';
        $expectedIdsParameterName = 'product_manufacturer_id';
        $expectedWhereCondition = '`product.manufacturer`.`id` IN (:product_manufacturer_id)';

        yield [
            $productDefinition,
            $filterFieldInformationWithIdsCollection,
            $context,
            $expectedFullyQualifiedTargetDefinitionIdField,
            $expectedIdsParameterName,
            '`product.manufacturer`.`id`',
            $expectedIds,
            $expectedWhereCondition,
        ];
    }

    /**
     * @param array<int, string> $expectedIds
     */

    #[DataProvider('extendQueryProvider')]
    public function testExtendQuery(
        ProductDefinition $productDefinition,
        FilterFieldInformationWithIdsCollection $filterFieldInformationWithIdsCollection,
        Context $context,
        string $expectedFullyQualifiedTargetDefinitionIdField,
        string $expectedIdsParameterName,
        string $expectedPathToTargetEntityId,
        array $expectedIds,
        string $expectedWhereCondition
    ): void {
        $queryBuilder = $this->createMock(QueryBuilder::class);

        $queryHelper = $this->createMock(EntityDefinitionQueryHelper::class);
        $queryHelper->expects($this->once())
            ->method('resolveAccessor')
            ->willReturnCallback(
                function (
                    string $accessorArgument,
                    EntityDefinition $definitionArgument,
                    string $rootArgument,
                    QueryBuilder $queryArgument
                ) use ($expectedFullyQualifiedTargetDefinitionIdField, $productDefinition, $queryBuilder): void {
                    $this->assertSame($expectedFullyQualifiedTargetDefinitionIdField, $accessorArgument);
                    $this->assertSame($productDefinition, $definitionArgument);
                    $this->assertSame('product', $rootArgument);
                    $this->assertSame($queryBuilder, $queryArgument);
                }
            );

        $queryBuilder->expects($this->once())
            ->method('setParameter')
            ->willReturnCallback(function (string $keyArgument, array $valueArgument, int $typeArgument) use (
                $expectedIdsParameterName,
                $expectedIds
            ): void {
                $this->assertSame($expectedIdsParameterName, $keyArgument);
                $this->assertSame(Uuid::fromHexToBytesList($expectedIds), $valueArgument);
                $this->assertSame(ArrayParameterType::BINARY, $typeArgument);
            });

        $expressionBuilder = $this->createMock(ExpressionBuilder::class);
        $expressionBuilder->method('or')
            ->willReturnCallback(function (string $expressionArgument, string ...$expressionsArgument) use (
                $expectedWhereCondition
            ): CompositeExpression {
                $this->assertSame($expectedWhereCondition, $expressionArgument);
                $this->assertCount(0, $expressionsArgument);

                return CompositeExpression::or($expressionArgument);
            });

        $queryBuilder->method('expr')
            ->willReturn($expressionBuilder);

        $queryBuilder->expects($this->once())
            ->method('andWhere')
            ->willReturnCallback(function (CompositeExpression $whereArgument) use (
                $expectedIdsParameterName,
                $expectedPathToTargetEntityId
            ): void {
                $this->assertSame('OR', $whereArgument->getType());
                $this->assertSame($expectedPathToTargetEntityId . ' IN (:' . $expectedIdsParameterName . ')', (string) $whereArgument);
            });

        $queryExtender = new QueryExtender($productDefinition, $queryHelper);
        $queryExtender->extendQuery($queryBuilder, $filterFieldInformationWithIdsCollection, $context);
    }
}
