<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Test\PHPUnit\Unit\Core\Content\ListingFilterConfiguration\Range\Aggregate;

use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Range\Aggregate\RangeListingFilterConfigurationTranslationCollection;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Range\Aggregate\RangeListingFilterConfigurationTranslationDefinition;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Range\Aggregate\RangeListingFilterConfigurationTranslationEntity;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Range\RangeListingFilterConfigurationDefinition;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;

#[CoversClass(RangeListingFilterConfigurationTranslationDefinition::class)]
final class RangeListingFilterConfigurationTranslationDefinitionTest extends TestCase
{
    private RangeListingFilterConfigurationTranslationDefinition $definition;

    protected function setUp(): void
    {
        $this->definition = new class() extends RangeListingFilterConfigurationTranslationDefinition {
            public function getDefinedFields(): FieldCollection
            {
                return $this->defineFields();
            }
        };
    }

    public function testDefineFields(): void
    {
        $fields = $this->definition->getDefinedFields();

        $this->assertCount(2, $fields);

        $displayNameField = $fields->get(0);
        $this->assertInstanceOf(StringField::class, $displayNameField);
        /** @var StringField $displayNameField */
        $this->assertSame('displayName', $displayNameField->getPropertyName());

        $unitField = $fields->get(1);
        $this->assertInstanceOf(StringField::class, $unitField);
        /** @var StringField $unitField */
        $this->assertSame('unit', $unitField->getPropertyName());
    }

    public function testGetCollectionClass(): void
    {
        $this->assertSame(RangeListingFilterConfigurationTranslationCollection::class, $this->definition->getCollectionClass());
    }

    public function testGetEntityClass(): void
    {
        $this->assertSame(RangeListingFilterConfigurationTranslationEntity::class, $this->definition->getEntityClass());
    }

    public function testGetEntityName(): void
    {
        $this->assertSame('itb_listing_filter_configuration_range_translation', $this->definition->getEntityName());
        $this->assertSame(RangeListingFilterConfigurationTranslationDefinition::ENTITY_NAME, $this->definition->getEntityName());
    }

    public function testParentDefinitionClass(): void
    {
        $reflectionClass = new \ReflectionClass(RangeListingFilterConfigurationTranslationDefinition::class);
        $method = $reflectionClass->getMethod('getParentDefinitionClass');
        $method->setAccessible(true);

        $parentClass = $method->invoke($this->definition);

        $this->assertSame(RangeListingFilterConfigurationDefinition::class, $parentClass);
    }
}
