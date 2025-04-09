<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Test\PHPUnit\Unit\Core\Content\ListingFilterConfiguration\MultiSelect;

use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\MultiSelect\MultiSelectListingFilterConfigurationEntity;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(MultiSelectListingFilterConfigurationEntity::class)]
final class MultiSelectListingFilterConfigurationEntityTest extends TestCase
{
    public function testGetAggregationName(): void
    {
        $entity = new MultiSelectListingFilterConfigurationEntity();
        $entity->setDalField('testProperty');

        $this->assertSame('test-property', $entity->getAggregationName());
    }

    public function testGetFilterName(): void
    {
        $entity = new MultiSelectListingFilterConfigurationEntity();
        $entity->setDalField('testProperty');

        $this->assertSame('test-property', $entity->getFilterName());
    }

    public function testInheritedMethods(): void
    {
        $entity = new MultiSelectListingFilterConfigurationEntity();
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

    public function testSpecificGettersAndSetters(): void
    {
        $entity = new MultiSelectListingFilterConfigurationEntity();

        $allowedElements = ['element1', 'element2'];
        $elementPrefix = 'prefix';
        $elementSuffix = 'suffix';
        $explicitElementSorting = ['sort1', 'sort2'];
        $forbiddenElements = ['forbidden1', 'forbidden2'];
        $sortingOrder = 'asc';

        $entity->setAllowedElements($allowedElements);
        $entity->setElementPrefix($elementPrefix);
        $entity->setElementSuffix($elementSuffix);
        $entity->setExplicitElementSorting($explicitElementSorting);
        $entity->setForbiddenElements($forbiddenElements);
        $entity->setSortingOrder($sortingOrder);

        $this->assertSame($allowedElements, $entity->getAllowedElements());
        $this->assertSame($elementPrefix, $entity->getElementPrefix());
        $this->assertSame($elementSuffix, $entity->getElementSuffix());
        $this->assertSame($explicitElementSorting, $entity->getExplicitElementSorting());
        $this->assertSame($forbiddenElements, $entity->getForbiddenElements());
        $this->assertSame($sortingOrder, $entity->getSortingOrder());
    }

    public function testTwigTemplateConstant(): void
    {
        $this->assertSame(
            '@Storefront/storefront/component/listing/filter/filter-multi-select.html.twig',
            MultiSelectListingFilterConfigurationEntity::TWIG_TEMPLATE
        );
    }
}
