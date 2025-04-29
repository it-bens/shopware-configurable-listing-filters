<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Test\PHPUnit\Unit\Core\Content\ListingFilterConfiguration\RangeInterval\Aggregate\Interval\Aggregate;

use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\RangeInterval\Aggregate\Interval\Aggregate\RangeIntervalListingFilterConfigurationIntervalTranslationCollection;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\RangeInterval\Aggregate\Interval\Aggregate\RangeIntervalListingFilterConfigurationIntervalTranslationDefinition;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\RangeInterval\Aggregate\Interval\Aggregate\RangeIntervalListingFilterConfigurationIntervalTranslationEntity;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\RangeInterval\Aggregate\Interval\RangeIntervalListingFilterConfigurationIntervalDefinition;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Shopware\Core\Framework\DataAbstractionLayer\DefinitionInstanceRegistry;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\ApiAware;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;

#[CoversClass(RangeIntervalListingFilterConfigurationIntervalTranslationDefinition::class)]
final class RangeIntervalListingFilterConfigurationIntervalTranslationDefinitionTest extends TestCase
{
    private RangeIntervalListingFilterConfigurationIntervalTranslationDefinition $definition;

    protected function setUp(): void
    {
        $this->definition = new RangeIntervalListingFilterConfigurationIntervalTranslationDefinition();
    }

    public function testDefineFields(): void
    {
        $registry = $this->createStub(DefinitionInstanceRegistry::class);
        $this->definition->compile($registry);
        $fields = $this->definition->getFields();

        $this->assertTrue($fields->has('languageId'));

        $this->assertTrue($fields->has('title'));
        $titleField = $fields->get('title');
        $this->assertInstanceOf(StringField::class, $titleField);
        $this->assertTrue($titleField->is(ApiAware::class));
        $this->assertSame('title', $titleField->getPropertyName());
        $this->assertSame('title', $titleField->getStorageName());
    }

    public function testEntityName(): void
    {
        $this->assertSame('itb_lfc_range_interval_interval_translation', $this->definition->getEntityName());
        $this->assertSame(
            'itb_lfc_range_interval_interval_translation',
            RangeIntervalListingFilterConfigurationIntervalTranslationDefinition::ENTITY_NAME
        );
    }

    public function testGetCollectionClass(): void
    {
        $this->assertSame(
            RangeIntervalListingFilterConfigurationIntervalTranslationCollection::class,
            $this->definition->getCollectionClass()
        );
    }

    public function testGetEntityClass(): void
    {
        $this->assertSame(RangeIntervalListingFilterConfigurationIntervalTranslationEntity::class, $this->definition->getEntityClass());
    }

    public function testGetParentDefinitionClass(): void
    {
        $reflectionClass = new \ReflectionClass(RangeIntervalListingFilterConfigurationIntervalTranslationDefinition::class);
        $method = $reflectionClass->getMethod('getParentDefinitionClass');

        $this->assertSame(RangeIntervalListingFilterConfigurationIntervalDefinition::class, $method->invoke($this->definition));
    }
}
