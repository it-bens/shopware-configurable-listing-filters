<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Test\PHPUnit\Unit\Core\Content\ListingFilterConfiguration\MultiSelect\Aggregate;

use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\MultiSelect\Aggregate\MultiSelectListingFilterConfigurationTranslationCollection;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\MultiSelect\Aggregate\MultiSelectListingFilterConfigurationTranslationDefinition;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\MultiSelect\Aggregate\MultiSelectListingFilterConfigurationTranslationEntity;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\MultiSelect\MultiSelectListingFilterConfigurationDefinition;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ListField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;

#[CoversClass(MultiSelectListingFilterConfigurationTranslationDefinition::class)]
final class MultiSelectListingFilterConfigurationTranslationDefinitionTest extends TestCase
{
    private MultiSelectListingFilterConfigurationTranslationDefinition $definition;

    protected function setUp(): void
    {
        $this->definition = new class() extends MultiSelectListingFilterConfigurationTranslationDefinition {
            public function getDefinedFields(): FieldCollection
            {
                return $this->defineFields();
            }
        };
    }

    public function testDefineFields(): void
    {
        $fields = $this->definition->getDefinedFields();

        $this->assertCount(6, $fields);

        $displayNameField = $fields->get(0);
        $this->assertInstanceOf(StringField::class, $displayNameField);
        /** @var StringField $displayNameField */
        $this->assertSame('displayName', $displayNameField->getPropertyName());

        $allowedElementsField = $fields->get(1);
        $this->assertInstanceOf(ListField::class, $allowedElementsField);
        /** @var ListField $allowedElementsField */
        $this->assertSame('allowedElements', $allowedElementsField->getPropertyName());

        $elementPrefixField = $fields->get(2);
        $this->assertInstanceOf(StringField::class, $elementPrefixField);
        /** @var StringField $elementPrefixField */
        $this->assertSame('elementPrefix', $elementPrefixField->getPropertyName());

        $elementSuffixField = $fields->get(3);
        $this->assertInstanceOf(StringField::class, $elementSuffixField);
        /** @var StringField $elementSuffixField */
        $this->assertSame('elementSuffix', $elementSuffixField->getPropertyName());

        $explicitElementSortingField = $fields->get(4);
        $this->assertInstanceOf(ListField::class, $explicitElementSortingField);
        /** @var ListField $explicitElementSortingField */
        $this->assertSame('explicitElementSorting', $explicitElementSortingField->getPropertyName());

        $forbiddenElementsField = $fields->get(5);
        $this->assertInstanceOf(ListField::class, $forbiddenElementsField);
        /** @var ListField $forbiddenElementsField */
        $this->assertSame('forbiddenElements', $forbiddenElementsField->getPropertyName());
    }

    public function testGetCollectionClass(): void
    {
        $this->assertSame(MultiSelectListingFilterConfigurationTranslationCollection::class, $this->definition->getCollectionClass());
    }

    public function testGetEntityClass(): void
    {
        $this->assertSame(MultiSelectListingFilterConfigurationTranslationEntity::class, $this->definition->getEntityClass());
    }

    public function testGetEntityName(): void
    {
        $this->assertSame('itb_listing_filter_configuration_multi_select_translation', $this->definition->getEntityName());
        $this->assertSame(MultiSelectListingFilterConfigurationTranslationDefinition::ENTITY_NAME, $this->definition->getEntityName());
    }

    public function testParentDefinitionClass(): void
    {
        $reflectionClass = new \ReflectionClass(MultiSelectListingFilterConfigurationTranslationDefinition::class);
        $method = $reflectionClass->getMethod('getParentDefinitionClass');
        $method->setAccessible(true);

        $parentClass = $method->invoke($this->definition);

        $this->assertSame(MultiSelectListingFilterConfigurationDefinition::class, $parentClass);
    }
}
