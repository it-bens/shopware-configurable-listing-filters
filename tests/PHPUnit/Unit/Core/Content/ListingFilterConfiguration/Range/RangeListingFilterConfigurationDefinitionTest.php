<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Test\PHPUnit\Unit\Core\Content\ListingFilterConfiguration\Range;

use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Range\RangeListingFilterConfigurationCollection;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Range\RangeListingFilterConfigurationDefinition;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Range\RangeListingFilterConfigurationEntity;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Shopware\Core\Framework\DataAbstractionLayer\Field\TranslatedField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\TranslationsAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;

#[CoversClass(RangeListingFilterConfigurationDefinition::class)]
final class RangeListingFilterConfigurationDefinitionTest extends TestCase
{
    private RangeListingFilterConfigurationDefinition $definition;

    protected function setUp(): void
    {
        $this->definition = new class() extends RangeListingFilterConfigurationDefinition {
            public function getDefinedFields(): FieldCollection
            {
                return $this->defineFields();
            }
        };
    }

    public function testDefineFields(): void
    {
        $fields = $this->definition->getDefinedFields();

        $this->assertCount(10, $fields);

        $unitField = $fields->get(8);
        $this->assertInstanceOf(TranslatedField::class, $unitField);
        /** @var TranslatedField $unitField */
        $this->assertSame('unit', $unitField->getPropertyName());

        $translationsField = $fields->get(9);
        $this->assertInstanceOf(TranslationsAssociationField::class, $translationsField);
        /** @var TranslationsAssociationField $translationsField */
        $this->assertSame(RangeListingFilterConfigurationDefinition::ENTITY_NAME . '_id', $translationsField->getReferenceField());
    }

    public function testGetCollectionClass(): void
    {
        $this->assertSame(RangeListingFilterConfigurationCollection::class, $this->definition->getCollectionClass());
    }

    public function testGetEntityClass(): void
    {
        $this->assertSame(RangeListingFilterConfigurationEntity::class, $this->definition->getEntityClass());
    }

    public function testGetEntityName(): void
    {
        $this->assertSame('itb_lfc_range', $this->definition->getEntityName());
        $this->assertSame(RangeListingFilterConfigurationDefinition::ENTITY_NAME, $this->definition->getEntityName());
    }
}
