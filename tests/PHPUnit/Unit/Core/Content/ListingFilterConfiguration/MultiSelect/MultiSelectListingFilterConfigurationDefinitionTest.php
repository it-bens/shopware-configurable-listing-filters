<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Test\PHPUnit\Unit\Core\Content\ListingFilterConfiguration\MultiSelect;

use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\MultiSelect\MultiSelectListingFilterConfigurationCollection;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\MultiSelect\MultiSelectListingFilterConfigurationDefinition;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\MultiSelect\MultiSelectListingFilterConfigurationEntity;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\TranslatedField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\TranslationsAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;

#[CoversClass(MultiSelectListingFilterConfigurationDefinition::class)]
final class MultiSelectListingFilterConfigurationDefinitionTest extends TestCase
{
    private MultiSelectListingFilterConfigurationDefinition $definition;

    protected function setUp(): void
    {
        $this->definition = new class() extends MultiSelectListingFilterConfigurationDefinition {
            public function getDefinedFields(): FieldCollection
            {
                return $this->defineFields();
            }
        };
    }

    public function testDefineFields(): void
    {
        $fields = $this->definition->getDefinedFields();

        $this->assertCount(15, $fields);

        $allowedElementsField = $fields->get(8);
        $this->assertInstanceOf(TranslatedField::class, $allowedElementsField);
        /** @var TranslatedField $allowedElementsField */
        $this->assertSame('allowedElements', $allowedElementsField->getPropertyName());

        $elementPrefixField = $fields->get(9);
        $this->assertInstanceOf(TranslatedField::class, $elementPrefixField);
        /** @var TranslatedField $elementPrefixField */
        $this->assertSame('elementPrefix', $elementPrefixField->getPropertyName());

        $elementSuffixField = $fields->get(10);
        $this->assertInstanceOf(TranslatedField::class, $elementSuffixField);
        /** @var TranslatedField $elementSuffixField */
        $this->assertSame('elementSuffix', $elementSuffixField->getPropertyName());

        $explicitElementSortingField = $fields->get(11);
        $this->assertInstanceOf(TranslatedField::class, $explicitElementSortingField);
        /** @var TranslatedField $explicitElementSortingField */
        $this->assertSame('explicitElementSorting', $explicitElementSortingField->getPropertyName());

        $forbiddenElementsField = $fields->get(12);
        $this->assertInstanceOf(TranslatedField::class, $forbiddenElementsField);
        /** @var TranslatedField $forbiddenElementsField */
        $this->assertSame('forbiddenElements', $forbiddenElementsField->getPropertyName());

        $sortingOrderField = $fields->get(13);
        $this->assertInstanceOf(StringField::class, $sortingOrderField);
        /** @var StringField $sortingOrderField */
        $this->assertSame('sortingOrder', $sortingOrderField->getPropertyName());

        $translationsField = $fields->get(14);
        $this->assertInstanceOf(TranslationsAssociationField::class, $translationsField);
        /** @var TranslationsAssociationField $translationsField */
        $this->assertSame(MultiSelectListingFilterConfigurationDefinition::ENTITY_NAME . '_id', $translationsField->getReferenceField());
    }

    public function testGetCollectionClass(): void
    {
        $this->assertSame(MultiSelectListingFilterConfigurationCollection::class, $this->definition->getCollectionClass());
    }

    public function testGetEntityClass(): void
    {
        $this->assertSame(MultiSelectListingFilterConfigurationEntity::class, $this->definition->getEntityClass());
    }

    public function testGetEntityName(): void
    {
        $this->assertSame('itb_listing_filter_configuration_multi_select', $this->definition->getEntityName());
        $this->assertSame(MultiSelectListingFilterConfigurationDefinition::ENTITY_NAME, $this->definition->getEntityName());
    }
}
