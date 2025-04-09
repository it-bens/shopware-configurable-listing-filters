<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Test\PHPUnit\Unit\Core\Content\ListingFilterConfiguration;

use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\ListingFilterConfigurationDefinition;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Shopware\Core\Framework\DataAbstractionLayer\Field\BoolField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\FkField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IdField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IntField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToOneAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\TranslatedField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;

#[CoversClass(ListingFilterConfigurationDefinition::class)]
final class ListingFilterConfigurationDefinitionTest extends TestCase
{
    private ListingFilterConfigurationDefinition $definition;

    protected function setUp(): void
    {
        $this->definition = new class() extends ListingFilterConfigurationDefinition {
            public function getEntityName(): string
            {
                return 'test_entity';
            }

            public function getDefinedFields(): FieldCollection
            {
                return $this->defineFields();
            }
        };
    }

    public function testDefineFields(): void
    {
        $fields = $this->definition->getDefinedFields();

        $this->assertCount(8, $fields);

        $idField = $fields->get(0);
        $this->assertInstanceOf(IdField::class, $idField);
        /** @var IdField $idField */
        $this->assertSame('id', $idField->getPropertyName());

        $dalField = $fields->get(1);
        $this->assertInstanceOf(StringField::class, $dalField);
        /** @var StringField $dalField */
        $this->assertSame('dalField', $dalField->getPropertyName());

        $displayNameField = $fields->get(2);
        $this->assertInstanceOf(TranslatedField::class, $displayNameField);
        /** @var TranslatedField $displayNameField */
        $this->assertSame('displayName', $displayNameField->getPropertyName());

        $enabledField = $fields->get(3);
        $this->assertInstanceOf(BoolField::class, $enabledField);
        /** @var BoolField $enabledField */
        $this->assertSame('enabled', $enabledField->getPropertyName());

        $positionField = $fields->get(4);
        $this->assertInstanceOf(IntField::class, $positionField);
        /** @var IntField $positionField */
        $this->assertSame('position', $positionField->getPropertyName());

        $twigTemplateField = $fields->get(5);
        $this->assertInstanceOf(StringField::class, $twigTemplateField);
        /** @var StringField $twigTemplateField */
        $this->assertSame('twigTemplate', $twigTemplateField->getPropertyName());

        $salesChannelIdField = $fields->get(6);
        $this->assertInstanceOf(FkField::class, $salesChannelIdField);
        /** @var FkField $salesChannelIdField */
        $this->assertSame('salesChannelId', $salesChannelIdField->getPropertyName());

        $salesChannelField = $fields->get(7);
        $this->assertInstanceOf(ManyToOneAssociationField::class, $salesChannelField);
        /** @var ManyToOneAssociationField $salesChannelField */
        $this->assertSame('salesChannel', $salesChannelField->getPropertyName());
    }
}
