<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Test\PHPUnit\Unit\Core\Framework\Adapter\Cache\CacheInvalidation\FilterFieldInformationWithIds\FilterFieldInformationWithIdsBuilder;

use ITB\ITBConfigurableListingFilters\Core\Framework\Adapter\Cache\CacheInvalidation\FilterFieldInformationWithIds\FilterFieldInformationWithIdsBuilder\ForTranslatedDefinition;
use ITB\ITBConfigurableListingFilters\Core\Framework\Adapter\Cache\CacheInvalidation\FilterFieldInformationWithIds\FilterFieldInformationWithIdsCollector;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Shopware\Core\Content\Product\ProductDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\CompiledFieldCollection;
use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\FkField;
use Shopware\Core\System\Language\LanguageDefinition;

/**
 * @phpstan-import-type IdsFromGetPrimaryKeys from FilterFieldInformationWithIdsCollector
 */
final class ForTranslatedDefinitionTest extends TestCase
{
    public static function provideBuildData(): \Generator
    {
        $builder = new ForTranslatedDefinition();
        $translationDefinition = self::createStub(EntityDefinition::class);
        $parentDefinition = self::createStub(EntityDefinition::class);
        $pathToTargetEntity = 'product.translations';
        $ids = [
            [
                'product_id' => 'uuid-prod-1',
                'language_id' => 'uuid-lang-1',
            ],
            [
                'product_id' => 'uuid-prod-2',
                'language_id' => 'uuid-lang-1',
            ],
            [
                'product_id' => 'uuid-prod-1',
                'language_id' => 'uuid-lang-2',
            ],
        ];
        $expectedIds = ['uuid-prod-1', 'uuid-prod-2', 'uuid-prod-1'];

        $fkField = self::createStub(FkField::class);
        $fkField->method('getReferenceDefinition')
            ->willReturn($parentDefinition);
        $fkField->method('getPropertyName')
            ->willReturn('product_id');

        $langFkField = self::createStub(FkField::class);
        $langFkField->method('getReferenceDefinition')
            ->willReturn(self::createStub(LanguageDefinition::class));
        $langFkField->method('getPropertyName')
            ->willReturn('language_id');

        $primaryKeys = self::createStub(CompiledFieldCollection::class);
        $primaryKeys->method('getIterator')
            ->willReturn(new \ArrayIterator([$fkField, $langFkField]));

        $translationDefinition->method('getPrimaryKeys')
            ->willReturn($primaryKeys);
        $translationDefinition->method('getParentDefinition')
            ->willReturn($parentDefinition);
        $translationDefinition->method('getEntityName')
            ->willReturn('product_translation');

        yield [$builder, $translationDefinition, $pathToTargetEntity, $ids, $parentDefinition, $expectedIds];
    }

    public static function provideBuildThrowsExceptionForMissingFkFieldData(): \Generator
    {
        $builder = new ForTranslatedDefinition();
        $translationDefinition = self::createStub(EntityDefinition::class);
        $pathToTargetEntity = 'category.translations';
        $ids = [[
            'category_id' => 'uuid-cat-1',
        ]];
        $entityName = 'category_translation';

        $primaryKeys = self::createStub(CompiledFieldCollection::class);
        $primaryKeys->method('getIterator')
            ->willReturn(new \ArrayIterator([]));

        $translationDefinition->method('getPrimaryKeys')
            ->willReturn($primaryKeys);
        $translationDefinition->method('getEntityName')
            ->willReturn($entityName);

        yield [$builder, $translationDefinition, $pathToTargetEntity, $ids, $entityName];
    }

    public static function provideBuildThrowsExceptionForMissingParentDefinitionData(): \Generator
    {
        $builder = new ForTranslatedDefinition();
        $productDefinition = self::createStub(ProductDefinition::class);
        $productDefinition->method('getEntityName')
            ->willReturn(ProductDefinition::ENTITY_NAME);
        $translationDefinition = self::createStub(EntityDefinition::class);
        $pathToTargetEntity = 'product.translations';
        $ids = [[
            'product_id' => 'uuid-prod-1',
        ]];
        $entityName = 'product_translation';

        $fkField = self::createStub(FkField::class);
        $fkField->method('getReferenceDefinition')
            ->willReturn($productDefinition);
        $fkField->method('getPropertyName')
            ->willReturn('product_id');

        $primaryKeys = self::createStub(CompiledFieldCollection::class);
        $primaryKeys->method('getIterator')
            ->willReturn(new \ArrayIterator([$fkField]));

        $translationDefinition->method('getPrimaryKeys')
            ->willReturn($primaryKeys);
        $translationDefinition->method('getParentDefinition')
            ->willReturn(null);
        $translationDefinition->method('getEntityName')
            ->willReturn($entityName);

        yield [$builder, $translationDefinition, $pathToTargetEntity, $ids, 'product_id', $entityName];
    }

    /**
     * @param IdsFromGetPrimaryKeys $ids
     * @param list<string> $expectedIds
     */
    #[DataProvider('provideBuildData')]
    public function testBuild(
        ForTranslatedDefinition $builder,
        EntityDefinition $targetEntityDefinition,
        string $pathToTargetEntity,
        array $ids,
        EntityDefinition $expectedParentDefinition,
        array $expectedIds
    ): void {
        $result = $builder->build($targetEntityDefinition, $pathToTargetEntity, $ids);

        $this->assertSame($pathToTargetEntity, $result->pathToTargetEntityDefinition);
        $this->assertSame($expectedParentDefinition, $result->targetEntityDefinition);
        $this->assertEquals($expectedIds, $result->ids);
    }

    /**
     * @param IdsFromGetPrimaryKeys $ids
     */
    #[DataProvider('provideBuildThrowsExceptionForMissingFkFieldData')]
    public function testBuildThrowsExceptionWhenFkFieldNotFound(
        ForTranslatedDefinition $builder,
        EntityDefinition $targetEntityDefinition,
        string $pathToTargetEntity,
        array $ids,
        string $entityName
    ): void {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage(sprintf(
            'FK field that does not reference the language not found in translation definition "%s"',
            $entityName
        ));

        $builder->build($targetEntityDefinition, $pathToTargetEntity, $ids);
    }

    /**
     * @param IdsFromGetPrimaryKeys $ids
     */
    #[DataProvider('provideBuildThrowsExceptionForMissingParentDefinitionData')]
    public function testBuildThrowsExceptionWhenParentDefinitionNotFound(
        ForTranslatedDefinition $builder,
        EntityDefinition $targetEntityDefinition,
        string $pathToTargetEntity,
        array $ids,
        string $fkFieldName,
        string $parentEntityName
    ): void {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage(sprintf(
            'Parent definition not found for FK field "%s" of translation definition "%s"',
            $fkFieldName,
            $parentEntityName
        ));

        $builder->build($targetEntityDefinition, $pathToTargetEntity, $ids);
    }
}
