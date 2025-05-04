<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Test\PHPUnit\Unit\Core\Framework\Adapter\Cache\CacheInvalidation\FilterFieldInformation;

use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Checkbox\CheckboxListingFilterConfigurationCollection;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\ListingFilterConfigurationCollection;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\MultiSelect\MultiSelectListingFilterConfigurationCollection;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\MultiSelect\MultiSelectListingFilterConfigurationEntity;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Range\RangeListingFilterConfigurationCollection;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\RangeInterval\RangeIntervalListingFilterConfigurationCollection;
use ITB\ITBConfigurableListingFilters\Core\Framework\Adapter\Cache\CacheInvalidation\FilterFieldInformation\FilterFieldInformation;
use ITB\ITBConfigurableListingFilters\Core\Framework\Adapter\Cache\CacheInvalidation\FilterFieldInformation\FilterFieldInformationCollector;
use ITB\ITBConfigurableListingFilters\Core\Framework\Adapter\Cache\CacheInvalidation\NonStaticEntityDefinitionQueryHelperInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use Shopware\Core\Content\Product\ProductDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Dbal\EntityDefinitionQueryHelper;
use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Field;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\TranslatedField;

#[CoversClass(FilterFieldInformationCollector::class)]
final class FilterFieldInformationCollectorTest extends TestCase
{
    public static function collectFieldNotFoundProvider(): \Generator
    {
        $productDefinition = self::createStub(ProductDefinition::class);
        $entityDefinitionOfTarget = self::createStub(EntityDefinition::class);

        $nonStaticQueryHelper = self::createStub(NonStaticEntityDefinitionQueryHelperInterface::class);
        $nonStaticQueryHelper->method('getAssociatedDefinition')
            ->willReturn($entityDefinitionOfTarget);

        $queryHelper = self::createStub(EntityDefinitionQueryHelper::class);
        $queryHelper->method('getField')
            ->willReturn(null);

        $collector = new FilterFieldInformationCollector($productDefinition, $nonStaticQueryHelper, $queryHelper);

        $listingFilterConfigurationCollection = self::provideListingFilterConfigurationCollection();

        yield [$collector, $listingFilterConfigurationCollection, 'Field "product.multiSelectField" not found in product definition'];
    }

    public static function collectProvider(): \Generator
    {
        $listingFilterConfigurationCollection = self::provideListingFilterConfigurationCollection();

        $expectedFullyQualifiedDalField = 'product.multiSelectField';
        $expectedField = self::createStub(StringField::class);

        yield [$listingFilterConfigurationCollection, $expectedFullyQualifiedDalField, $expectedField];
    }

    public static function collectTranslationDefinitionNotFoundProvider(): \Generator
    {
        $productDefinition = self::createStub(ProductDefinition::class);
        $entityDefinitionOfTarget = self::createStub(EntityDefinition::class);
        $entityDefinitionOfTarget->method('getTranslationDefinition')
            ->willReturn(null);

        $nonStaticQueryHelper = self::createStub(NonStaticEntityDefinitionQueryHelperInterface::class);
        $nonStaticQueryHelper->method('getAssociatedDefinition')
            ->willReturn($entityDefinitionOfTarget);

        $field = self::createStub(TranslatedField::class);
        $queryHelper = self::createStub(EntityDefinitionQueryHelper::class);
        $queryHelper->method('getField')
            ->willReturn($field);

        $collector = new FilterFieldInformationCollector($productDefinition, $nonStaticQueryHelper, $queryHelper);

        $listingFilterConfigurationCollection = self::provideListingFilterConfigurationCollection();

        yield [
            $collector,
            $listingFilterConfigurationCollection,
            'Translation definition not found for field "product.multiSelectField"',
        ];
    }

    public static function collectWithTranslatedFieldProvider(): \Generator
    {
        $productDefinition = self::createStub(ProductDefinition::class);

        $entityDefinitionOfTarget = self::createStub(EntityDefinition::class);
        $entityDefinitionOfTargetTranslation = self::createStub(EntityDefinition::class);
        $entityDefinitionOfTarget->method('getTranslationDefinition')
            ->willReturn($entityDefinitionOfTargetTranslation);

        $nonStaticQueryHelper = self::createStub(NonStaticEntityDefinitionQueryHelperInterface::class);
        $nonStaticQueryHelper->method('getAssociatedDefinition')
            ->willReturn($entityDefinitionOfTarget);

        $field = self::createStub(TranslatedField::class);
        $queryHelper = self::createStub(EntityDefinitionQueryHelper::class);
        $queryHelper->method('getField')
            ->willReturn($field);

        $listingFilterConfigurationCollection = self::provideListingFilterConfigurationCollection();

        yield [
            $productDefinition,
            $nonStaticQueryHelper,
            $queryHelper,
            $listingFilterConfigurationCollection,
            $entityDefinitionOfTargetTranslation,
        ];
    }

    #[DataProvider('collectProvider')]
    public function testCollect(
        ListingFilterConfigurationCollection $listingFilterConfigurationCollection,
        string $expectedFullyQualifiedDalField,
        Field $expectedField
    ): void {
        $productDefinition = $this->createStub(ProductDefinition::class);
        $entityDefinitionOfTarget = $this->createStub(EntityDefinition::class);

        $nonStaticQueryHelper = $this->createMock(NonStaticEntityDefinitionQueryHelperInterface::class);
        $nonStaticQueryHelper->method('getAssociatedDefinition')
            ->willReturnCallback(function (EntityDefinition $definitionArgument, string $fieldArgument) use (
                $productDefinition,
                $expectedFullyQualifiedDalField,
                $entityDefinitionOfTarget
            ): Stub {
                $this->assertSame($definitionArgument, $productDefinition);
                $this->assertSame($fieldArgument, $expectedFullyQualifiedDalField);

                return $entityDefinitionOfTarget;
            });

        $queryHelper = $this->createMock(EntityDefinitionQueryHelper::class);
        $queryHelper->method('getField')
            ->willReturnCallback(function (string $fieldArgument, EntityDefinition $definitionArgument, string $entityName) use (
                $productDefinition,
                $expectedFullyQualifiedDalField,
                $expectedField
            ): Field {
                $this->assertSame($fieldArgument, $expectedFullyQualifiedDalField);
                $this->assertSame($definitionArgument, $productDefinition);
                $this->assertSame('product', $entityName);

                return $expectedField;
            });

        $collector = new FilterFieldInformationCollector($productDefinition, $nonStaticQueryHelper, $queryHelper);
        $filterFieldInformationCollection = $collector->collect($listingFilterConfigurationCollection);

        $filterFieldInformationCollectionReflection = new ReflectionClass($filterFieldInformationCollection);
        $filterFieldInformationsPropertyReflection = $filterFieldInformationCollectionReflection->getProperty('filterFieldInformations');
        $filterFieldInformations = $filterFieldInformationsPropertyReflection->getValue($filterFieldInformationCollection);

        $this->assertIsArray($filterFieldInformations);
        $this->assertCount(1, $filterFieldInformations);
        $this->assertInstanceOf(FilterFieldInformation::class, $filterFieldInformations[0]);
    }

    #[DataProvider('collectFieldNotFoundProvider')]
    public function testCollectFieldNotFound(
        FilterFieldInformationCollector $collector,
        ListingFilterConfigurationCollection $listingFilterConfigurationCollection,
        string $expectedExceptionMessage,
    ): void {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage($expectedExceptionMessage);

        $collector->collect($listingFilterConfigurationCollection);
    }

    #[DataProvider('collectTranslationDefinitionNotFoundProvider')]
    public function testCollectTranslationDefinitionNotFound(
        FilterFieldInformationCollector $collector,
        ListingFilterConfigurationCollection $listingFilterConfigurationCollection,
        string $expectedExceptionMessage,
    ): void {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage($expectedExceptionMessage);

        $collector->collect($listingFilterConfigurationCollection);
    }

    #[DataProvider('collectWithTranslatedFieldProvider')]
    public function testCollectWithTranslatedField(
        ProductDefinition $productDefinition,
        NonStaticEntityDefinitionQueryHelperInterface $nonStaticQueryHelper,
        EntityDefinitionQueryHelper $queryHelper,
        ListingFilterConfigurationCollection $listingFilterConfigurationCollection,
        EntityDefinition $expectedEntityDefinitionOfTargetTranslation
    ): void {
        $collector = new FilterFieldInformationCollector($productDefinition, $nonStaticQueryHelper, $queryHelper);
        $filterFieldInformationCollection = $collector->collect($listingFilterConfigurationCollection);

        $filterFieldInformationCollectionReflection = new ReflectionClass($filterFieldInformationCollection);
        $filterFieldInformationsPropertyReflection = $filterFieldInformationCollectionReflection->getProperty('filterFieldInformations');
        /** @var array<FilterFieldInformation> $filterFieldInformations */
        $filterFieldInformations = $filterFieldInformationsPropertyReflection->getValue($filterFieldInformationCollection);

        $this->assertSame($expectedEntityDefinitionOfTargetTranslation, $filterFieldInformations[0]->targetDefinition);
    }

    private static function provideListingFilterConfigurationCollection(): ListingFilterConfigurationCollection
    {
        $multiSelectListingFilterConfigurationEntity = new MultiSelectListingFilterConfigurationEntity();
        $multiSelectListingFilterConfigurationEntity->setUniqueIdentifier('multi-select');
        $multiSelectListingFilterConfigurationEntity->setDalField('multiSelectField');
        $multiSelectListingFilterConfigurationEntity->setPosition(1);

        $multiSelectListingFilterConfigurationCollection = new MultiSelectListingFilterConfigurationCollection();
        $multiSelectListingFilterConfigurationCollection->add($multiSelectListingFilterConfigurationEntity);

        $checkboxListingFilterConfigurationCollection = new CheckboxListingFilterConfigurationCollection();
        $rangeListingFilterConfigurationCollection = new RangeListingFilterConfigurationCollection();
        $rangeIntervalListingFilterConfigurationCollection = new RangeIntervalListingFilterConfigurationCollection();

        return new ListingFilterConfigurationCollection(
            $checkboxListingFilterConfigurationCollection,
            $multiSelectListingFilterConfigurationCollection,
            $rangeListingFilterConfigurationCollection,
            $rangeIntervalListingFilterConfigurationCollection
        );
    }
}
