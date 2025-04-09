<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Test\PHPUnit\Unit\ListingFilter\MultiSelect\Storefront;

use ITB\ITBConfigurableListingFilters\ListingFilter\MultiSelect\Storefront\ElementTranslation;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(ElementTranslation::class)]
final class ElementTranslationTest extends TestCase
{
    #[DataProvider('translationProvider')]
    public function testConstruction(string $name, string $expectedName): void
    {
        $translation = new ElementTranslation($name);
        $this->assertSame($expectedName, $translation->getName());
    }

    public static function translationProvider(): \Generator
    {
        yield 'simple translation' => ['Red', 'Red'];
        yield 'translation with whitespace' => ['  Blue  ', '  Blue  '];
        yield 'translation with special characters' => ['Special — Character', 'Special — Character'];
    }
}
