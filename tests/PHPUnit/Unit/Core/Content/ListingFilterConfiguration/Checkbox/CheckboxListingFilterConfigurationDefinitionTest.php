<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Test\PHPUnit\Unit\Core\Content\ListingFilterConfiguration\Checkbox;

use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Checkbox\CheckboxListingFilterConfigurationCollection;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Checkbox\CheckboxListingFilterConfigurationDefinition;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Checkbox\CheckboxListingFilterConfigurationEntity;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Shopware\Core\Framework\DataAbstractionLayer\Field\TranslationsAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;

#[CoversClass(CheckboxListingFilterConfigurationDefinition::class)]
final class CheckboxListingFilterConfigurationDefinitionTest extends TestCase
{
    private CheckboxListingFilterConfigurationDefinition $definition;

    protected function setUp(): void
    {
        $this->definition = new class() extends CheckboxListingFilterConfigurationDefinition {
            public function getDefinedFields(): FieldCollection
            {
                return $this->defineFields();
            }
        };
    }

    public function testDefineFields(): void
    {
        $fields = $this->definition->getDefinedFields();

        $this->assertCount(9, $fields);

        $translationsField = $fields->get(8);
        $this->assertInstanceOf(TranslationsAssociationField::class, $translationsField);
        /** @var TranslationsAssociationField $translationsField */
        $this->assertSame(CheckboxListingFilterConfigurationDefinition::ENTITY_NAME . '_id', $translationsField->getReferenceField());
    }

    public function testGetCollectionClass(): void
    {
        $this->assertSame(CheckboxListingFilterConfigurationCollection::class, $this->definition->getCollectionClass());
    }

    public function testGetEntityClass(): void
    {
        $this->assertSame(CheckboxListingFilterConfigurationEntity::class, $this->definition->getEntityClass());
    }

    public function testGetEntityName(): void
    {
        $this->assertSame('itb_lfc_checkbox', $this->definition->getEntityName());
        $this->assertSame(CheckboxListingFilterConfigurationDefinition::ENTITY_NAME, $this->definition->getEntityName());
    }
}
