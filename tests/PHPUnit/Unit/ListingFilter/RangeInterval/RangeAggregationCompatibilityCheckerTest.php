<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Test\PHPUnit\Unit\ListingFilter\RangeInterval;

use ITB\ITBConfigurableListingFilters\ListingFilter\RangeInterval\RangeAggregationCompatibilityChecker;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Shopware\Core\Content\Product\ProductDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Dbal\EntityDefinitionQueryHelper;
use Shopware\Core\Framework\DataAbstractionLayer\DefinitionInstanceRegistry;
use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Field;
use Shopware\Core\Framework\DataAbstractionLayer\Field\FloatField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IntField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\PriceField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;

#[CoversClass(RangeAggregationCompatibilityChecker::class)]
final class RangeAggregationCompatibilityCheckerTest extends TestCase
{
    private RangeAggregationCompatibilityChecker $compatibilityChecker;

    private MockObject&EntityDefinition $productDefinitionMock;

    private MockObject&EntityDefinitionQueryHelper $queryHelperMock;

    protected function setUp(): void
    {
        parent::setUp();
        $this->queryHelperMock = $this->createMock(EntityDefinitionQueryHelper::class);
        $definitionRegistryMock = $this->createMock(DefinitionInstanceRegistry::class);
        $this->productDefinitionMock = $this->createMock(EntityDefinition::class);

        $definitionRegistryMock
            ->method('getByEntityName')
            ->with(ProductDefinition::ENTITY_NAME)
            ->willReturn($this->productDefinitionMock);

        $this->compatibilityChecker = new RangeAggregationCompatibilityChecker($this->queryHelperMock, $definitionRegistryMock);
    }

    /**
     * @return iterable<string, array{0: string, 1: class-string<Field>|null, 2: bool}>
     */
    public static function provideFieldTypes(): iterable
    {
        yield 'PriceField is compatible' => ['product.price', PriceField::class, true];
        yield 'FloatField is compatible' => ['product.width', FloatField::class, true];
        yield 'IntField is compatible' => ['product.stock', IntField::class, true];
        yield 'StringField is not compatible' => ['product.name', StringField::class, false];
        yield 'Other Field is not compatible' => ['product.customField', Field::class, false]; // Using base Field class as example
        yield 'Field not found (null) is not compatible' => ['product.nonExistentField', null, false];
    }

    /**
     * @param class-string<Field>|null $fieldTypeClass The class name of the field type to mock, or null if getField should return null.
     */
    #[DataProvider('provideFieldTypes')]
    public function testIsDalFieldRangeAggregationCompatible(string $dalField, ?string $fieldTypeClass, bool $expectedResult): void
    {
        $fieldMock = $fieldTypeClass !== null && $fieldTypeClass !== '' && $fieldTypeClass !== '0' ? $this->createMock(
            $fieldTypeClass
        ) : null;

        $this->queryHelperMock
            ->expects($this->once())
            ->method('getField')
            ->with($dalField, $this->identicalTo($this->productDefinitionMock), ProductDefinition::ENTITY_NAME)
            ->willReturn($fieldMock);

        $result = $this->compatibilityChecker->isDalFieldRangeAggregationCompatible($dalField);

        $this->assertSame($expectedResult, $result);
    }
}
