<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Test\PHPUnit\Unit\ListingFilter\Checkbox\Dal;

use ITB\ITBConfigurableListingFilters\ListingFilter\Checkbox\Dal\RequestValue;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(RequestValue::class)]
final class RequestValueTest extends TestCase
{
    public static function isFilteredProvider(): \Generator
    {
        yield 'is filtered' => [new RequestValue(true), true];
        yield 'is not filtered' => [new RequestValue(false), false];
    }

    #[DataProvider('isFilteredProvider')]
    public function testIsFiltered(RequestValue $requestValue, bool $expectedIsFiltered): void
    {
        $this->assertSame($expectedIsFiltered, $requestValue->isFiltered());
    }
}
