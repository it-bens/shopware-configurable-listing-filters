<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Test\PHPUnit\Unit\Core\Content\ListingFilterConfiguration\RangeInterval;

use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\RangeInterval\Aggregate\RangeIntervalListingFilterConfigurationIntervalCollection;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\RangeInterval\RangeIntervalListingFilterConfigurationEntity;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(RangeIntervalListingFilterConfigurationEntity::class)]
final class RangeIntervalListingFilterConfigurationEntityTest extends TestCase
{
    public static function dalFieldProvider(): \Generator
    {
        yield 'simple field name' => [
            'dalField' => 'price',
            'expectedResult' => 'price',
        ];

        yield 'camelCase field name' => [
            'dalField' => 'productPrice',
            'expectedResult' => 'product-price',
        ];

        yield 'field name with dots' => [
            'dalField' => 'product.price',
            'expectedResult' => 'product-price',
        ];

        yield 'complex field name' => [
            'dalField' => 'product.manufacturer.name',
            'expectedResult' => 'product-manufacturer-name',
        ];
    }

    #[DataProvider('dalFieldProvider')]
    public function testGetAggregationName(string $dalField, string $expectedResult): void
    {
        $entity = new RangeIntervalListingFilterConfigurationEntity();
        $entity->setDalField($dalField);

        $this->assertSame($expectedResult, $entity->getAggregationName());
    }

    #[DataProvider('dalFieldProvider')]
    public function testGetFilterName(string $dalField, string $expectedResult): void
    {
        $entity = new RangeIntervalListingFilterConfigurationEntity();
        $entity->setDalField($dalField);

        $this->assertSame($expectedResult, $entity->getFilterName());
    }

    public function testGetterAndSetterMethods(): void
    {
        $entity = new RangeIntervalListingFilterConfigurationEntity();

        $elementPrefix = '€';
        $elementSuffix = ',-';
        $intervalCollection = new RangeIntervalListingFilterConfigurationIntervalCollection();

        $entity->setElementPrefix($elementPrefix);
        $entity->setElementSuffix($elementSuffix);
        $entity->setIntervals($intervalCollection);

        $this->assertSame($elementPrefix, $entity->getElementPrefix());
        $this->assertSame($elementSuffix, $entity->getElementSuffix());
        $this->assertSame($intervalCollection, $entity->getIntervals());
    }

    public function testTwigTemplate(): void
    {
        $entity = new RangeIntervalListingFilterConfigurationEntity();

        $this->assertSame('@Storefront/storefront/component/listing/filter/filter-multi-select.html.twig', $entity::TWIG_TEMPLATE);
    }
}
