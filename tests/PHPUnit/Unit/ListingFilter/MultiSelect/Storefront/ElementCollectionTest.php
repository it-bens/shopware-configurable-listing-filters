<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Test\PHPUnit\Unit\ListingFilter\MultiSelect\Storefront;

use ITB\ITBConfigurableListingFilters\ListingFilter\MultiSelect\Storefront\Element;
use ITB\ITBConfigurableListingFilters\ListingFilter\MultiSelect\Storefront\ElementCollection;
use PHPUnit\Framework\TestCase;

final class ElementCollectionTest extends TestCase
{
    public function testGetElementsWithExplicitSorting(): void
    {
        $element1 = new Element('element1', 'element1');
        $element2 = new Element('element2', 'element2');
        $element3 = new Element('element3', 'element3');

        $collection = new ElementCollection([$element1, $element2, $element3], ['element3', 'element1']);

        $this->assertSame([$element3, $element1, $element2], $collection->getElements());
    }

    public function testGetElementsWithoutExplicitSorting(): void
    {
        $element1 = new Element('element1', 'element1');
        $element2 = new Element('element2', 'element2');

        $collection = new ElementCollection([$element1, $element2], null);

        $this->assertSame([$element1, $element2], $collection->getElements());
    }

    public function testIsEmptyReturnsFalse(): void
    {
        $element = new Element('element1', 'element1');

        $collection = new ElementCollection([$element], null);

        $this->assertFalse($collection->isEmpty());
    }

    public function testIsEmptyReturnsTrue(): void
    {
        $collection = new ElementCollection([], null);

        $this->assertTrue($collection->isEmpty());
    }
}
