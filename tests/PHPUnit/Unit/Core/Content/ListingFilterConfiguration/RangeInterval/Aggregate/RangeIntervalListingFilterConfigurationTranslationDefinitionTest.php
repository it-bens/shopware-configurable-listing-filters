<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Test\PHPUnit\Unit\Core\Content\ListingFilterConfiguration\RangeInterval\Aggregate;

use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\RangeInterval\Aggregate\RangeIntervalListingFilterConfigurationTranslationCollection;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\RangeInterval\Aggregate\RangeIntervalListingFilterConfigurationTranslationDefinition;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\RangeInterval\Aggregate\RangeIntervalListingFilterConfigurationTranslationEntity;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\RangeInterval\RangeIntervalListingFilterConfigurationDefinition;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Shopware\Core\Framework\DataAbstractionLayer\DefinitionInstanceRegistry;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\ApiAware;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;

#[CoversClass(RangeIntervalListingFilterConfigurationTranslationDefinition::class)]
final class RangeIntervalListingFilterConfigurationTranslationDefinitionTest extends TestCase
{
    private RangeIntervalListingFilterConfigurationTranslationDefinition $definition;

    protected function setUp(): void
    {
        $this->definition = new RangeIntervalListingFilterConfigurationTranslationDefinition();
    }

    public function testDefineFieldsWithoutAssociations(): void
    {
        $registry = $this->createStub(DefinitionInstanceRegistry::class);
        $this->definition->compile($registry);
        $fields = $this->definition->getFields();

        $this->assertTrue($fields->has('languageId'));
        $this->assertTrue($fields->has('displayName'));

        $this->assertTrue($fields->has('elementPrefix'));
        $elementPrefixField = $fields->get('elementPrefix');
        $this->assertInstanceOf(StringField::class, $elementPrefixField);
        $this->assertTrue($elementPrefixField->is(ApiAware::class));

        $this->assertTrue($fields->has('elementSuffix'));
        $elementSuffixField = $fields->get('elementSuffix');
        $this->assertInstanceOf(StringField::class, $elementSuffixField);
        $this->assertTrue($elementSuffixField->is(ApiAware::class));
    }

    public function testEntityName(): void
    {
        $this->assertSame('itb_lfc_range_interval_translation', $this->definition->getEntityName());
        $this->assertSame(
            'itb_lfc_range_interval_translation',
            RangeIntervalListingFilterConfigurationTranslationDefinition::ENTITY_NAME
        );
    }

    public function testGetCollectionClass(): void
    {
        $this->assertSame(RangeIntervalListingFilterConfigurationTranslationCollection::class, $this->definition->getCollectionClass());
    }

    public function testGetEntityClass(): void
    {
        $this->assertSame(RangeIntervalListingFilterConfigurationTranslationEntity::class, $this->definition->getEntityClass());
    }

    public function testGetParentDefinitionClass(): void
    {
        $reflectionClass = new \ReflectionClass(RangeIntervalListingFilterConfigurationTranslationDefinition::class);
        $method = $reflectionClass->getMethod('getParentDefinitionClass');

        $this->assertSame(RangeIntervalListingFilterConfigurationDefinition::class, $method->invoke($this->definition));
    }
}
