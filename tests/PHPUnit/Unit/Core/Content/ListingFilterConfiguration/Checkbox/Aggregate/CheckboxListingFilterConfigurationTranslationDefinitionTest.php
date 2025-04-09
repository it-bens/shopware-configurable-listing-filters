<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Test\PHPUnit\Unit\Core\Content\ListingFilterConfiguration\Checkbox\Aggregate;

use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Checkbox\Aggregate\CheckboxListingFilterConfigurationTranslationCollection;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Checkbox\Aggregate\CheckboxListingFilterConfigurationTranslationDefinition;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Checkbox\Aggregate\CheckboxListingFilterConfigurationTranslationEntity;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Checkbox\CheckboxListingFilterConfigurationDefinition;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;

#[CoversClass(CheckboxListingFilterConfigurationTranslationDefinition::class)]
final class CheckboxListingFilterConfigurationTranslationDefinitionTest extends TestCase
{
    private CheckboxListingFilterConfigurationTranslationDefinition $definition;

    protected function setUp(): void
    {
        $this->definition = new class() extends CheckboxListingFilterConfigurationTranslationDefinition {
            public function getDefinedFields(): FieldCollection
            {
                return $this->defineFields();
            }
        };
    }

    public function testDefineFields(): void
    {
        $fields = $this->definition->getDefinedFields();

        $this->assertCount(1, $fields);
    }

    public function testGetCollectionClass(): void
    {
        $this->assertSame(CheckboxListingFilterConfigurationTranslationCollection::class, $this->definition->getCollectionClass());
    }

    public function testGetEntityClass(): void
    {
        $this->assertSame(CheckboxListingFilterConfigurationTranslationEntity::class, $this->definition->getEntityClass());
    }

    public function testGetEntityName(): void
    {
        $this->assertSame('itb_listing_filter_configuration_checkbox_translation', $this->definition->getEntityName());
        $this->assertSame(CheckboxListingFilterConfigurationTranslationDefinition::ENTITY_NAME, $this->definition->getEntityName());
    }

    public function testParentDefinitionClass(): void
    {
        $reflectionClass = new \ReflectionClass(CheckboxListingFilterConfigurationTranslationDefinition::class);
        $method = $reflectionClass->getMethod('getParentDefinitionClass');
        $method->setAccessible(true);

        $parentClass = $method->invoke($this->definition);

        $this->assertSame(CheckboxListingFilterConfigurationDefinition::class, $parentClass);
    }
}
