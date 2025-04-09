<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Test\PHPUnit\Unit\Core\Content\ListingFilterConfiguration;

use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\ListingFilterConfigurationEntity;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Shopware\Core\System\SalesChannel\SalesChannelEntity;

#[CoversClass(ListingFilterConfigurationEntity::class)]
final class ListingFilterConfigurationEntityTest extends TestCase
{
    public function testGetFullyQualifiedDalField(): void
    {
        $entity = new class() extends ListingFilterConfigurationEntity {
            public function getFilterName(): string
            {
                return 'testFilterName';
            }
        };

        $dalField = 'testDalField';
        $entity->setDalField($dalField);

        $this->assertSame('product.testDalField', $entity->getFullyQualifiedDalField());
    }

    public function testGetterAndSetterMethods(): void
    {
        $entity = new class() extends ListingFilterConfigurationEntity {
            public function getFilterName(): string
            {
                return 'testFilterName';
            }
        };

        $dalField = 'testDalField';
        $displayName = 'testDisplayName';
        $enabled = true;
        $position = 5;
        $salesChannelEntity = new SalesChannelEntity();
        $salesChannelId = 'testSalesChannelId';
        $twigTemplate = 'testTwigTemplate';
        $uniqueName = 'testUniqueName';

        $entity->setDalField($dalField);
        $entity->setDisplayName($displayName);
        $entity->setEnabled($enabled);
        $entity->setPosition($position);
        $entity->setSalesChannelEntity($salesChannelEntity);
        $entity->setSalesChannelId($salesChannelId);
        $entity->setTwigTemplate($twigTemplate);
        $entity->setUniqueName($uniqueName);

        $this->assertSame($dalField, $entity->getDalField());
        $this->assertSame($displayName, $entity->getDisplayName());
        $this->assertEquals($enabled, $entity->getEnabled());
        $this->assertSame($position, $entity->getPosition());
        $this->assertEquals($salesChannelEntity, $entity->getSalesChannelEntity());
        $this->assertSame($salesChannelId, $entity->getSalesChannelId());
        $this->assertSame($twigTemplate, $entity->getTwigTemplate());
        $this->assertSame($uniqueName, $entity->getUniqueName());
    }

    public function testSlugifyDalField(): void
    {
        $entity = new class() extends ListingFilterConfigurationEntity {
            public function getFilterName(): string
            {
                return 'test';
            }

            public function publicSlugifyDalField(string $dalField): string
            {
                return $this->slugifyDalField($dalField);
            }
        };

        $this->assertSame('my-property', $entity->publicSlugifyDalField('myProperty'));
        $this->assertSame('property-with-dots', $entity->publicSlugifyDalField('property.with.dots'));
    }
}
