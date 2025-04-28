<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Test\PHPUnit\Unit\ListingFilter\RangeInterval\Storefront;

use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\RangeInterval\Aggregate\Interval\RangeIntervalListingFilterConfigurationIntervalEntity;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\RangeInterval\RangeIntervalListingFilterConfigurationEntity;
use ITB\ITBConfigurableListingFilters\ListingFilter\MultiSelect\Storefront\Element;
use ITB\ITBConfigurableListingFilters\ListingFilter\RangeInterval\Storefront\ElementBuilder;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Shopware\Core\Framework\Uuid\Uuid;

#[CoversClass(ElementBuilder::class)]
final class ElementBuilderTest extends TestCase
{
    private ElementBuilder $elementBuilder;

    protected function setUp(): void
    {
        parent::setUp();
        $this->elementBuilder = new ElementBuilder();
    }

    /**
     * @return iterable<string, array{
     *     min: int|null,
     *     max: int|null,
     *     prefix: string|null,
     *     suffix: string|null,
     *     expectedText: string
     * }>
     */
    public static function elementDataProvider(): iterable
    {
        yield 'Min and Max set, no prefix/suffix' => [
            'min' => 10,
            'max' => 50,
            'prefix' => null,
            'suffix' => null,
            'expectedText' => '10 - 50',
        ];

        yield 'Min and Max set, with prefix/suffix' => [
            'min' => 0,
            'max' => 100,
            'prefix' => '€',
            'suffix' => '!',
            'expectedText' => '€0! - €100!',
        ];

        yield 'Min null, Max set, no prefix/suffix' => [
            'min' => null,
            'max' => 99,
            'prefix' => null,
            'suffix' => null,
            'expectedText' => '< 99',
        ];

        yield 'Min null, Max set, with prefix/suffix' => [
            'min' => null,
            'max' => 50,
            'prefix' => '$',
            'suffix' => ' USD',
            'expectedText' => '< $50 USD',
        ];

        yield 'Min set, Max null, no prefix/suffix' => [
            'min' => 200,
            'max' => null,
            'prefix' => null,
            'suffix' => null,
            'expectedText' => '> 200',
        ];

        yield 'Min set, Max null, with prefix/suffix' => [
            'min' => 150,
            'max' => null,
            'prefix' => '£',
            'suffix' => ' GBP',
            'expectedText' => '> £150 GBP',
        ];

        yield 'Min and Max set, empty prefix/suffix' => [
            'min' => 5,
            'max' => 15,
            'prefix' => '',
            'suffix' => '',
            'expectedText' => '5 - 15',
        ];
    }

    #[DataProvider('elementDataProvider')]
    public function testBuildElement(?int $min, ?int $max, ?string $prefix, ?string $suffix, string $expectedText): void
    {
        $intervalId = Uuid::randomHex();

        $configMock = $this->createMock(RangeIntervalListingFilterConfigurationEntity::class);
        $configMock->method('getElementPrefix')
            ->willReturn($prefix);
        $configMock->method('getElementSuffix')
            ->willReturn($suffix);

        $intervalMock = $this->createMock(RangeIntervalListingFilterConfigurationIntervalEntity::class);
        $intervalMock->method('getId')
            ->willReturn($intervalId);
        $intervalMock->method('getMin')
            ->willReturn($min);
        $intervalMock->method('getMax')
            ->willReturn($max);
        $intervalMock->method('getRangeIntervalListingFilterConfiguration')
            ->willReturn($configMock);

        $element = $this->elementBuilder->buildElement($intervalMock);

        $this->assertInstanceOf(Element::class, $element);
        $this->assertSame($intervalId, $element->name);
        $this->assertSame($expectedText, $element->text);
    }

    public function testBuildElementThrowsExceptionWhenMinAndMaxAreNull(): void
    {
        $configMock = $this->createMock(RangeIntervalListingFilterConfigurationEntity::class);
        $configMock->method('getElementPrefix')
            ->willReturn(null);
        $configMock->method('getElementSuffix')
            ->willReturn(null);

        $intervalMock = $this->createMock(RangeIntervalListingFilterConfigurationIntervalEntity::class);
        $intervalMock->method('getId')
            ->willReturn(Uuid::randomHex());
        $intervalMock->method('getMin')
            ->willReturn(null);
        $intervalMock->method('getMax')
            ->willReturn(null);
        $intervalMock->method('getRangeIntervalListingFilterConfiguration')
            ->willReturn($configMock);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Both min and max are null. This should be prevented by the pre-persistence validation.');

        $this->elementBuilder->buildElement($intervalMock);
    }
}
