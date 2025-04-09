<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Test\PHPUnit\Unit\ListingFilter\MultiSelect\Dal;

use ITB\ITBConfigurableListingFilters\ListingFilter\MultiSelect\Dal\RequestValue;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(RequestValue::class)]
final class RequestValueTest extends TestCase
{
    public static function isFilteredProvider(): \Generator
    {
        yield 'is filtered with one value' => [new RequestValue(['red']), true];
        yield 'is filtered with multiple values' => [new RequestValue(['red', 'blue', 'green']), true];
        yield 'is not filtered with empty array' => [new RequestValue([]), false];
    }

    #[DataProvider('isFilteredProvider')]
    public function testIsFiltered(RequestValue $requestValue, bool $expectedIsFiltered): void
    {
        $this->assertSame($expectedIsFiltered, $requestValue->isFiltered());
    }
}
