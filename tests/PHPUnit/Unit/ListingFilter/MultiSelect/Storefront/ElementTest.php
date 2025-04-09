<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Test\PHPUnit\Unit\ListingFilter\MultiSelect\Storefront;

use ITB\ITBConfigurableListingFilters\ListingFilter\MultiSelect\Storefront\Element;
use ITB\ITBConfigurableListingFilters\ListingFilter\MultiSelect\Storefront\ElementTranslation;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(Element::class)]
final class ElementTest extends TestCase
{
    public static function getIdProvider(): \Generator
    {
        yield 'basic element' => [new Element('red', 'Red Color'), 'red', 'Red Color'];

        yield 'element with special characters' => [new Element('option-1', 'Special — Character'), 'option-1'];
    }

    public static function getTranslatedProvider(): \Generator
    {
        yield 'basic element' => [new Element('red', 'Red Color'), 'Red Color'];

        yield 'element with special characters' => [new Element('option-1', 'Special — Character'), 'Special — Character'];
    }

    #[DataProvider('getIdProvider')]
    public function testGetId(Element $element, string $expectedId): void
    {
        $this->assertSame($expectedId, $element->getId());
    }

    #[DataProvider('getTranslatedProvider')]
    public function testGetTranslated(Element $element, string $expectedText): void
    {
        $translation = $element->getTranslated();
        $this->assertInstanceOf(ElementTranslation::class, $translation);
        $this->assertSame($expectedText, $translation->getName());
    }
}
