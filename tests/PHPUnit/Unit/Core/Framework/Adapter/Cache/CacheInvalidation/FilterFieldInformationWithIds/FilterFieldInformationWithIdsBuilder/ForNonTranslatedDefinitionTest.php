<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Test\PHPUnit\Unit\Core\Framework\Adapter\Cache\CacheInvalidation\FilterFieldInformationWithIds\FilterFieldInformationWithIdsBuilder;

use ITB\ITBConfigurableListingFilters\Core\Framework\Adapter\Cache\CacheInvalidation\FilterFieldInformationWithIds\FilterFieldInformationWithIdsBuilder\ForNonTranslatedDefinition;
use ITB\ITBConfigurableListingFilters\Core\Framework\Adapter\Cache\CacheInvalidation\FilterFieldInformationWithIds\FilterFieldInformationWithIdsCollector;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Shopware\Core\Framework\DataAbstractionLayer\CompiledFieldCollection;
use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IdField;

/**
 * @phpstan-import-type IdsFromGetPrimaryKeys from FilterFieldInformationWithIdsCollector
 */
#[CoversClass(ForNonTranslatedDefinition::class)]
final class ForNonTranslatedDefinitionTest extends TestCase
{
    public static function provideBuildData(): \Generator
    {
        $targetEntityDefinition = self::createStub(EntityDefinition::class);
        $pathToTargetEntityDefinition = 'product.manufacturer';
        $ids = [
            [
                'id' => 'uuid-1',
            ],
            [
                'id' => 'uuid-2',
            ],
        ];
        $expectedIds = ['uuid-1', 'uuid-2'];
        $primaryKeyField = new IdField('id', 'id');
        $primaryKeyField->addFlags(new PrimaryKey());

        $fieldCollection = self::createStub(CompiledFieldCollection::class);
        $fieldCollection->method('first')
            ->willReturn($primaryKeyField);

        $targetEntityDefinition->method('getPrimaryKeys')
            ->willReturn($fieldCollection);
        $targetEntityDefinition->method('getEntityName')
            ->willReturn('manufacturer');

        yield [new ForNonTranslatedDefinition(), $targetEntityDefinition, $pathToTargetEntityDefinition, $ids, $expectedIds];
    }

    public static function provideBuildThrowsExceptionData(): \Generator
    {
        $targetEntityDefinition = self::createStub(EntityDefinition::class);
        $pathToTargetEntityDefinition = 'product.category';
        $ids = [
            [
                'id' => 'uuid-1',
            ],
        ];
        $entityName = 'category';

        $fieldCollection = self::createStub(CompiledFieldCollection::class);
        $fieldCollection->method('first')
            ->willReturn(null);

        $targetEntityDefinition->method('getPrimaryKeys')
            ->willReturn($fieldCollection);
        $targetEntityDefinition->method('getEntityName')
            ->willReturn($entityName);

        yield [new ForNonTranslatedDefinition(), $targetEntityDefinition, $pathToTargetEntityDefinition, $ids, $entityName];
    }

    /**
     * @param IdsFromGetPrimaryKeys $ids
     * @param list<string> $expectedIds
     */
    #[DataProvider('provideBuildData')]
    public function testBuild(
        ForNonTranslatedDefinition $builder,
        EntityDefinition $targetEntityDefinition,
        string $pathToTargetEntityDefinition,
        array $ids,
        array $expectedIds
    ): void {
        $filterFieldInformationWithIds = $builder->build($targetEntityDefinition, $pathToTargetEntityDefinition, $ids);

        $this->assertSame($pathToTargetEntityDefinition, $filterFieldInformationWithIds->pathToTargetEntityDefinition);
        $this->assertSame($targetEntityDefinition, $filterFieldInformationWithIds->targetEntityDefinition);
        $this->assertEquals($expectedIds, $filterFieldInformationWithIds->ids);
    }

    /**
     * @param IdsFromGetPrimaryKeys $ids
     */
    #[DataProvider('provideBuildThrowsExceptionData')]
    public function testBuildThrowsExceptionWhenPrimaryKeyNotFound(
        ForNonTranslatedDefinition $builder,
        EntityDefinition $targetEntityDefinition,
        string $pathToTargetEntityDefinition,
        array $ids,
        string $entityName
    ): void {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage(sprintf('Primary key field not found in definition "%s"', $entityName));

        $builder->build($targetEntityDefinition, $pathToTargetEntityDefinition, $ids);
    }
}
