<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Test\PHPUnit\Unit\Core\Content\ListingFilterConfiguration\RangeInterval\Aggregate\Interval;

use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\RangeInterval\Aggregate\Interval\RangeIntervalListingFilterConfigurationIntervalCollection;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\RangeInterval\Aggregate\Interval\RangeIntervalListingFilterConfigurationIntervalDefinition;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\RangeInterval\Aggregate\Interval\RangeIntervalListingFilterConfigurationIntervalEntity;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\RangeInterval\RangeIntervalListingFilterConfigurationDefinition;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Shopware\Core\Framework\DataAbstractionLayer\DefinitionInstanceRegistry;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\ApiAware;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IdField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IntField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToOneAssociationField;

#[CoversClass(RangeIntervalListingFilterConfigurationIntervalDefinition::class)]
final class RangeIntervalListingFilterConfigurationIntervalDefinitionTest extends TestCase
{
    private RangeIntervalListingFilterConfigurationIntervalDefinition $definition;

    protected function setUp(): void
    {
        $this->definition = new RangeIntervalListingFilterConfigurationIntervalDefinition();
    }

    public function testDefineFieldsWithoutAssociations(): void
    {
        $registry = $this->createStub(DefinitionInstanceRegistry::class);
        $this->definition->compile($registry);
        $fields = $this->definition->getFields();

        $this->assertTrue($fields->has('id'));
        $idField = $fields->get('id');
        $this->assertInstanceOf(IdField::class, $idField);
        $this->assertTrue($idField->is(Required::class));
        $this->assertTrue($idField->is(ApiAware::class));
        $this->assertTrue($idField->is(PrimaryKey::class));

        $this->assertTrue($fields->has('min'));
        $minField = $fields->get('min');
        $this->assertInstanceOf(IntField::class, $minField);
        $this->assertTrue($minField->is(ApiAware::class));

        $this->assertTrue($fields->has('max'));
        $maxField = $fields->get('max');
        $this->assertInstanceOf(IntField::class, $maxField);
        $this->assertTrue($maxField->is(ApiAware::class));

        $this->assertTrue($fields->has('position'));
        $positionField = $fields->get('position');
        $this->assertInstanceOf(IntField::class, $positionField);
        $this->assertTrue($positionField->is(ApiAware::class));
    }

    public function testDefineFieldsWithRangeIntervalListingFilterAssociation(): void
    {
        $rangeIntervalListingFilterConfigurationDefinition = new RangeIntervalListingFilterConfigurationDefinition();
        $registry = $this->createMock(DefinitionInstanceRegistry::class);
        $registry->method('getByClassOrEntityName')
            ->willReturnCallback(function (string $className) use (
                $rangeIntervalListingFilterConfigurationDefinition
            ): RangeIntervalListingFilterConfigurationDefinition {
                $this->assertSame(RangeIntervalListingFilterConfigurationDefinition::class, $className);

                return $rangeIntervalListingFilterConfigurationDefinition;
            });
        $this->definition->compile($registry);
        $fields = $this->definition->getFields();

        $this->assertTrue($fields->has('rangeIntervalListingFilterConfiguration'));
        $associationField = $fields->get('rangeIntervalListingFilterConfiguration');
        $this->assertInstanceOf(ManyToOneAssociationField::class, $associationField);

        $fkFieldName = RangeIntervalListingFilterConfigurationDefinition::ENTITY_NAME . '_id';
        $this->assertSame($fkFieldName, $associationField->getStorageName());
        $this->assertSame($rangeIntervalListingFilterConfigurationDefinition, $associationField->getReferenceDefinition());
        $this->assertSame('id', $associationField->getReferenceField());
    }

    public function testEntityName(): void
    {
        $this->assertSame('itb_lfc_range_interval_interval', $this->definition->getEntityName());
        $this->assertSame('itb_lfc_range_interval_interval', RangeIntervalListingFilterConfigurationIntervalDefinition::ENTITY_NAME);
    }

    public function testGetCollectionClass(): void
    {
        $this->assertSame(RangeIntervalListingFilterConfigurationIntervalCollection::class, $this->definition->getCollectionClass());
    }

    public function testGetEntityClass(): void
    {
        $this->assertSame(RangeIntervalListingFilterConfigurationIntervalEntity::class, $this->definition->getEntityClass());
    }

    public function testGetParentDefinitionClass(): void
    {
        // Use reflection to access protected method
        $reflectionClass = new \ReflectionClass(RangeIntervalListingFilterConfigurationIntervalDefinition::class);
        $method = $reflectionClass->getMethod('getParentDefinitionClass');
        $method->setAccessible(true);

        $this->assertSame(RangeIntervalListingFilterConfigurationDefinition::class, $method->invoke($this->definition));
    }
}
