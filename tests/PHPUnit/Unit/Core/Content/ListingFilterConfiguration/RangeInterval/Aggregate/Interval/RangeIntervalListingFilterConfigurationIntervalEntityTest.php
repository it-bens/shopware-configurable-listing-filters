<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Test\PHPUnit\Unit\Core\Content\ListingFilterConfiguration\RangeInterval\Aggregate\Interval;

use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\RangeInterval\Aggregate\Interval\RangeIntervalListingFilterConfigurationIntervalEntity;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\RangeInterval\RangeIntervalListingFilterConfigurationEntity;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\RangeFilter;

#[CoversClass(RangeIntervalListingFilterConfigurationIntervalEntity::class)]
final class RangeIntervalListingFilterConfigurationIntervalEntityTest extends TestCase
{
    public static function rangeFilterDataProvider(): \Generator
    {
        yield 'both min and max are set' => [
            'min' => 100,
            'max' => 200,
            'expectedRange' => [
                RangeFilter::GTE => 100,
                RangeFilter::LTE => 200,
            ],
        ];

        yield 'only min is set' => [
            'min' => 100,
            'max' => null,
            'expectedRange' => [
                RangeFilter::GTE => 100,
            ],
        ];

        yield 'only max is set' => [
            'min' => null,
            'max' => 200,
            'expectedRange' => [
                RangeFilter::LTE => 200,
            ],
        ];

        yield 'both min and max are null' => [
            'min' => null,
            'max' => null,
            'expectedRange' => [],
        ];
    }

    public function testConfigurationAssociation(): void
    {
        $entity = new RangeIntervalListingFilterConfigurationIntervalEntity();
        $configEntity = new RangeIntervalListingFilterConfigurationEntity();

        $entity->setRangeIntervalListingFilterConfiguration($configEntity);

        $this->assertSame($configEntity, $entity->getRangeIntervalListingFilterConfiguration());
    }

    public function testEntityIdTrait(): void
    {
        $entity = new RangeIntervalListingFilterConfigurationIntervalEntity();
        $id = 'test-entity-id';

        $reflectionClass = new \ReflectionClass($entity);
        $property = $reflectionClass->getProperty('id');
        $property->setValue($entity, $id);

        $this->assertSame($id, $entity->getId());
    }

    public function testGetCountAggregationName(): void
    {
        $entity = new RangeIntervalListingFilterConfigurationIntervalEntity();
        $configEntity = new RangeIntervalListingFilterConfigurationEntity();
        $configEntity->setDalField('product.price');

        $entity->setId('test-interval-id');
        $entity->setRangeIntervalListingFilterConfiguration($configEntity);

        $this->assertSame('product-price_test-interval-id', $entity->getCountAggregationName());
    }

    public function testGetIdFromCountAggregationName(): void
    {
        $entity = new RangeIntervalListingFilterConfigurationIntervalEntity();
        $configEntity = new RangeIntervalListingFilterConfigurationEntity();
        $configEntity->setDalField('product.price');

        $entity->setRangeIntervalListingFilterConfiguration($configEntity);

        $this->assertSame('test-interval-id', $entity->getIdFromCountAggregationName('product-price_test-interval-id'));
    }

    /**
     * @param array{
     *      gte?: int,
     *      lte?: int
     *  } $expectedRange
     */
    #[DataProvider('rangeFilterDataProvider')]
    public function testGetRangeForFilter(?int $min, ?int $max, array $expectedRange): void
    {
        $entity = new RangeIntervalListingFilterConfigurationIntervalEntity();
        $entity->setMin($min);
        $entity->setMax($max);

        $this->assertSame($expectedRange, $entity->getRangeForFilter());
    }

    public function testGetterAndSetterMethods(): void
    {
        $entity = new RangeIntervalListingFilterConfigurationIntervalEntity();

        $min = 100;
        $max = 200;
        $position = 5;
        $configId = 'test-config-id';

        $entity->setMin($min);
        $entity->setMax($max);
        $entity->setPosition($position);
        $entity->setRangeIntervalListingFilterConfigurationId($configId);

        $this->assertSame($min, $entity->getMin());
        $this->assertSame($max, $entity->getMax());
        $this->assertSame($position, $entity->getPosition());
        $this->assertSame($configId, $entity->getRangeIntervalListingFilterConfigurationId());
    }
}
