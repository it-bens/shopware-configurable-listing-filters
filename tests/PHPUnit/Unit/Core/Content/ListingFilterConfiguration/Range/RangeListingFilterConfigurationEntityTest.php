<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Test\PHPUnit\Unit\Core\Content\ListingFilterConfiguration\Range;

use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Range\RangeListingFilterConfigurationEntity;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(RangeListingFilterConfigurationEntity::class)]
final class RangeListingFilterConfigurationEntityTest extends TestCase
{
    public function testGetFilterName(): void
    {
        $entity = new RangeListingFilterConfigurationEntity();
        $entity->setDalField('testProperty');

        $this->assertSame('test-property', $entity->getFilterName());
    }

    public function testGetMaximalValueAggregationName(): void
    {
        $entity = new RangeListingFilterConfigurationEntity();
        $entity->setDalField('testProperty');

        $this->assertSame('max-test-property', $entity->getMaximalValueAggregationName());
    }

    public function testGetMinimalValueAggregationName(): void
    {
        $entity = new RangeListingFilterConfigurationEntity();
        $entity->setDalField('testProperty');

        $this->assertSame('min-test-property', $entity->getMinimalValueAggregationName());
    }

    public function testInheritedMethods(): void
    {
        $entity = new RangeListingFilterConfigurationEntity();
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

    public function testNestedPropertyAggregationName(): void
    {
        $entity = new RangeListingFilterConfigurationEntity();
        $entity->setDalField('parent.child');

        $this->assertSame('parent-min-child', $entity->getMinimalValueAggregationName());
        $this->assertSame('parent-max-child', $entity->getMaximalValueAggregationName());
    }

    public function testTwigTemplateConstant(): void
    {
        $this->assertSame(
            '@Storefront/storefront/component/listing/filter/filter-range.html.twig',
            RangeListingFilterConfigurationEntity::TWIG_TEMPLATE
        );
    }

    public function testUnitGetterAndSetter(): void
    {
        $entity = new RangeListingFilterConfigurationEntity();
        $unit = 'kg';

        $entity->setUnit($unit);

        $this->assertSame($unit, $entity->getUnit());
    }
}
