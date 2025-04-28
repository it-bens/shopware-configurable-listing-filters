<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Test\PHPUnit\Unit\Core\Content\ListingFilterConfiguration\RangeInterval;

use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\RangeInterval\Aggregate\RangeIntervalListingFilterConfigurationIntervalDefinition;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\RangeInterval\Aggregate\RangeIntervalListingFilterConfigurationTranslationDefinition;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\RangeInterval\RangeIntervalListingFilterConfigurationCollection;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\RangeInterval\RangeIntervalListingFilterConfigurationDefinition;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\RangeInterval\RangeIntervalListingFilterConfigurationEntity;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Shopware\Core\Framework\DataAbstractionLayer\DefinitionInstanceRegistry;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\ApiAware;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\OneToManyAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\TranslatedField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\TranslationsAssociationField;

#[CoversClass(RangeIntervalListingFilterConfigurationDefinition::class)]
final class RangeIntervalListingFilterConfigurationDefinitionTest extends TestCase
{
    private RangeIntervalListingFilterConfigurationDefinition $definition;

    protected function setUp(): void
    {
        $this->definition = new RangeIntervalListingFilterConfigurationDefinition();
    }

    public function testDefineFieldsWithIntervalsAssociation(): void
    {
        $intervalDefinition = new RangeIntervalListingFilterConfigurationIntervalDefinition();
        $registry = $this->createMock(DefinitionInstanceRegistry::class);
        $registry->method('getByClassOrEntityName')
            ->willReturnCallback(function (string $className) use (
                $intervalDefinition
            ): RangeIntervalListingFilterConfigurationIntervalDefinition {
                $this->assertSame(RangeIntervalListingFilterConfigurationIntervalDefinition::class, $className);

                return $intervalDefinition;
            });
        $this->definition->compile($registry);
        $fields = $this->definition->getFields();

        $this->assertTrue($fields->has('intervals'));
        $intervalsField = $fields->get('intervals');
        $this->assertInstanceOf(OneToManyAssociationField::class, $intervalsField);
        $this->assertTrue($intervalsField->is(ApiAware::class));
        $this->assertTrue($intervalsField->is(Required::class));

        $this->assertSame('intervals', $intervalsField->getPropertyName());
        $this->assertSame($intervalDefinition, $intervalsField->getReferenceDefinition());
        $this->assertSame(RangeIntervalListingFilterConfigurationDefinition::ENTITY_NAME . '_id', $intervalsField->getReferenceField());
    }

    public function testDefineFieldsWithoutAssociations(): void
    {
        $registry = $this->createStub(DefinitionInstanceRegistry::class);
        $this->definition->compile($registry);
        $fields = $this->definition->getFields();

        $this->assertTrue($fields->has('elementPrefix'));
        $elementPrefixField = $fields->get('elementPrefix');
        $this->assertInstanceOf(TranslatedField::class, $elementPrefixField);
        $this->assertTrue($elementPrefixField->is(ApiAware::class));

        $this->assertTrue($fields->has('elementSuffix'));
        $elementSuffixField = $fields->get('elementSuffix');
        $this->assertInstanceOf(TranslatedField::class, $elementSuffixField);
        $this->assertTrue($elementSuffixField->is(ApiAware::class));
    }

    public function testDefineFieldsWithTranslationsAssociation(): void
    {
        $translationDefinition = new RangeIntervalListingFilterConfigurationTranslationDefinition();
        $registry = $this->createMock(DefinitionInstanceRegistry::class);
        $registry->method('getByClassOrEntityName')
            ->willReturnCallback(function (string $className) use (
                $translationDefinition
            ): RangeIntervalListingFilterConfigurationTranslationDefinition {
                $this->assertSame(RangeIntervalListingFilterConfigurationTranslationDefinition::class, $className);

                return $translationDefinition;
            });
        $this->definition->compile($registry);
        $fields = $this->definition->getFields();

        $this->assertTrue($fields->has('translations'));
        $translationsField = $fields->get('translations');
        $this->assertInstanceOf(TranslationsAssociationField::class, $translationsField);
        $this->assertTrue($translationsField->is(ApiAware::class));
        $this->assertTrue($translationsField->is(Required::class));

        $this->assertSame($translationDefinition, $translationsField->getReferenceDefinition());
        $this->assertSame(RangeIntervalListingFilterConfigurationDefinition::ENTITY_NAME . '_id', $translationsField->getReferenceField());
    }

    public function testEntityName(): void
    {
        $this->assertSame('itb_listing_filter_configuration_range_interval', $this->definition->getEntityName());
        $this->assertSame(
            'itb_listing_filter_configuration_range_interval',
            RangeIntervalListingFilterConfigurationDefinition::ENTITY_NAME
        );
    }

    public function testGetCollectionClass(): void
    {
        $this->assertSame(RangeIntervalListingFilterConfigurationCollection::class, $this->definition->getCollectionClass());
    }

    public function testGetEntityClass(): void
    {
        $this->assertSame(RangeIntervalListingFilterConfigurationEntity::class, $this->definition->getEntityClass());
    }
}
