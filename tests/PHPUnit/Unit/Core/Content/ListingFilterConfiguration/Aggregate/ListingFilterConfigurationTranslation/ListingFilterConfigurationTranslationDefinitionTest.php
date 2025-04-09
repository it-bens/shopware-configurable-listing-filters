<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Test\PHPUnit\Unit\Core\Content\ListingFilterConfiguration\Aggregate\ListingFilterConfigurationTranslation;

use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Aggregate\ListingFilterConfigurationTranslation\ListingFilterConfigurationTranslationDefinition;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;

#[CoversClass(ListingFilterConfigurationTranslationDefinition::class)]
final class ListingFilterConfigurationTranslationDefinitionTest extends TestCase
{
    public function testDefineFields(): void
    {
        $definition = new class() extends ListingFilterConfigurationTranslationDefinition {
            public function getEntityName(): string
            {
                return 'test_translation_entity';
            }

            public function getDefinedFields(): FieldCollection
            {
                return $this->defineFields();
            }
        };

        $fields = $definition->getDefinedFields();

        $this->assertCount(1, $fields);

        $displayNameField = $fields->get(0);
        $this->assertInstanceOf(StringField::class, $displayNameField);
        /** @var StringField $displayNameField */
        $this->assertSame('displayName', $displayNameField->getPropertyName());
    }
}
