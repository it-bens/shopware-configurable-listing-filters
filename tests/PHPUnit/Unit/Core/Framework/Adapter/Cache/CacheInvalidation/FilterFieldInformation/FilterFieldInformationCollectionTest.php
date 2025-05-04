<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Test\PHPUnit\Unit\Core\Framework\Adapter\Cache\CacheInvalidation\FilterFieldInformation;

use ITB\ITBConfigurableListingFilters\Core\Framework\Adapter\Cache\CacheInvalidation\FilterFieldInformation\FilterFieldInformation;
use ITB\ITBConfigurableListingFilters\Core\Framework\Adapter\Cache\CacheInvalidation\FilterFieldInformation\FilterFieldInformationCollection;
use ITB\ITBConfigurableListingFilters\Core\Framework\Adapter\Cache\CacheInvalidation\FilterFieldInformation\FilterFieldTargetInformation;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;
use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Field;

#[CoversClass(FilterFieldInformationCollection::class)]
final class FilterFieldInformationCollectionTest extends TestCase
{
    public static function provideFieldPropertyNamesData(): \Generator
    {
        /** @var Stub&EntityDefinition $definition1 */
        $definition1 = self::createStub(EntityDefinition::class);
        $definition1->method('getEntityName')
            ->willReturn('entity1');

        /** @var Stub&EntityDefinition $definition2 */
        $definition2 = self::createStub(EntityDefinition::class);
        $definition2->method('getEntityName')
            ->willReturn('entity2');

        /** @var Stub&Field $field1 */
        $field1 = self::createStub(Field::class);
        $field1->method('getPropertyName')
            ->willReturn('field1_prop');

        /** @var Stub&Field $field2 */
        $field2 = self::createStub(Field::class);
        $field2->method('getPropertyName')
            ->willReturn('field2_prop');

        /** @var Stub&Field $field3 */
        $field3 = self::createStub(Field::class);
        $field3->method('getPropertyName')
            ->willReturn('field3_prop');

        $infos = [
            new FilterFieldInformation('entity1.field1_prop', $definition1, $field1),
            new FilterFieldInformation('entity2.field2_prop', $definition2, $field2),
            new FilterFieldInformation('entity1.sub.field3_prop', $definition1, $field3),
        ];

        yield 'entity with multiple fields' => [
            'infosToAdd' => $infos,
            'entityNameToTest' => 'entity1',
            'expectedNames' => ['field1_prop', 'field3_prop'],
        ];

        yield 'entity with single field' => [
            'infosToAdd' => $infos,
            'entityNameToTest' => 'entity2',
            'expectedNames' => ['field2_prop'],
        ];

        yield 'unknown entity' => [
            'infosToAdd' => $infos,
            'entityNameToTest' => 'unknown_entity',
            'expectedNames' => [],
        ];

        yield 'empty collection' => [
            'infosToAdd' => [],
            'entityNameToTest' => 'entity1',
            'expectedNames' => [],
        ];
    }

    public static function provideFilterFieldTargetInformationsData(): \Generator
    {
        /** @var Stub&EntityDefinition $definition1 */
        $definition1 = self::createStub(EntityDefinition::class);
        $definition1->method('getEntityName')
            ->willReturn('entity1');

        /** @var Stub&EntityDefinition $definition2 */
        $definition2 = self::createStub(EntityDefinition::class);
        $definition2->method('getEntityName')
            ->willReturn('entity2');

        /** @var Stub&Field $field1 */
        $field1 = self::createStub(Field::class);
        /** @var Stub&Field $field2 */
        $field2 = self::createStub(Field::class);
        /** @var Stub&Field $field3 */
        $field3 = self::createStub(Field::class);

        $infos = [
            new FilterFieldInformation('product.manufacturer.name', $definition1, $field1),
            new FilterFieldInformation('product.categories.name', $definition2, $field2),
            new FilterFieldInformation('customer.firstName', $definition1, $field3),
        ];

        $expectedTargetInfos = [
            new FilterFieldTargetInformation('product.manufacturer', $definition1),
            new FilterFieldTargetInformation('product.categories', $definition2),
            new FilterFieldTargetInformation('customer', $definition1),
        ];

        yield 'standard case' => [
            'infosToAdd' => $infos,
            'expectedTargetInfos' => $expectedTargetInfos,
        ];

        yield 'empty collection' => [
            'infosToAdd' => [],
            'expectedTargetInfos' => [],
        ];

        /** @var Stub&EntityDefinition $definition3 */
        $definition3 = self::createStub(EntityDefinition::class);
        $definition3->method('getEntityName')
            ->willReturn('entity3');
        /** @var Stub&Field $field4 */
        $field4 = self::createStub(Field::class);
        $infosDuplicatePath = [
            new FilterFieldInformation('product.options.name', $definition1, $field1),
            new FilterFieldInformation('product.options.group.name', $definition3, $field4), // Same path prefix, different definition
        ];
        $expectedDuplicatePath = [
            new FilterFieldTargetInformation('product.options', $definition1),
            new FilterFieldTargetInformation('product.options.group', $definition3),
        ];

        yield 'duplicate path prefix different definition' => [
            'infosToAdd' => $infosDuplicatePath,
            'expectedTargetInfos' => $expectedDuplicatePath,
        ];
    }

    /**
     * @param iterable<FilterFieldInformation> $infosToAdd
     * @param list<string> $expectedNames
     */
    #[DataProvider('provideFieldPropertyNamesData')]
    public function testGetFieldPropertyNamesForDefinition(iterable $infosToAdd, string $entityNameToTest, array $expectedNames): void
    {
        $collection = new FilterFieldInformationCollection();
        foreach ($infosToAdd as $info) {
            $collection->add($info);
        }

        $actualNames = $collection->getFieldPropertyNamesForDefinition($entityNameToTest);
        $this->assertEqualsCanonicalizing($expectedNames, $actualNames);
    }

    /**
     * @param iterable<FilterFieldInformation> $infosToAdd
     * @param list<FilterFieldTargetInformation> $expectedTargetInfos
     */
    #[DataProvider('provideFilterFieldTargetInformationsData')]
    public function testGetFilterFieldTargetInformations(iterable $infosToAdd, array $expectedTargetInfos): void
    {
        $collection = new FilterFieldInformationCollection();
        foreach ($infosToAdd as $info) {
            $collection->add($info);
        }

        $actualTargetInfos = $collection->getFilterFieldTargetInformations();
        $this->assertCount(count($expectedTargetInfos), $actualTargetInfos);
        foreach ($expectedTargetInfos as $i => $expected) {
            $this->assertSame($expected->pathToTargetEntity, $actualTargetInfos[$i]->pathToTargetEntity);
            $this->assertSame($expected->targetEntityDefinition, $actualTargetInfos[$i]->targetEntityDefinition);
        }
    }
}
