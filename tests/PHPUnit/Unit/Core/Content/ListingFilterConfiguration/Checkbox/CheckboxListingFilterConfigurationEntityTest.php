<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Test\PHPUnit\Unit\Core\Content\ListingFilterConfiguration\Checkbox;

use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Checkbox\CheckboxListingFilterConfigurationEntity;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(CheckboxListingFilterConfigurationEntity::class)]
final class CheckboxListingFilterConfigurationEntityTest extends TestCase
{
    public function testGetAggregationName(): void
    {
        $entity = new CheckboxListingFilterConfigurationEntity();
        $entity->setDalField('testProperty');

        $this->assertSame('test-property', $entity->getAggregationName());
    }

    public function testGetFilterName(): void
    {
        $entity = new CheckboxListingFilterConfigurationEntity();
        $entity->setDalField('testProperty');

        $this->assertSame('test-property', $entity->getFilterName());
    }

    public function testInheritedMethods(): void
    {
        $entity = new CheckboxListingFilterConfigurationEntity();
        $entity->setDalField('testDalField');
        $entity->setDisplayName('testDisplayName');
        $entity->setEnabled(true);
        $entity->setPosition(5);
        $entity->setSalesChannelId('testSalesChannelId');
        $entity->setTwigTemplate('testTwigTemplate');
        $entity->setUniqueName('testUniqueName');

        $this->assertSame('testDalField', $entity->getDalField());
        $this->assertSame('testDisplayName', $entity->getDisplayName());
        $this->assertTrue($entity->getEnabled());
        $this->assertSame(5, $entity->getPosition());
        $this->assertSame('testSalesChannelId', $entity->getSalesChannelId());
        $this->assertSame('testTwigTemplate', $entity->getTwigTemplate());
        $this->assertSame('testUniqueName', $entity->getUniqueName());
        $this->assertSame('product.testDalField', $entity->getFullyQualifiedDalField());
    }

    public function testTwigTemplateConstant(): void
    {
        $this->assertSame(
            '@Storefront/storefront/component/listing/filter/filter-boolean.html.twig',
            CheckboxListingFilterConfigurationEntity::TWIG_TEMPLATE
        );
    }
}
