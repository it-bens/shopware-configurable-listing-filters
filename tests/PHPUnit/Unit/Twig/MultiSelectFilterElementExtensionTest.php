<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Test\PHPUnit\Unit\Twig;

use ITB\ITBConfigurableListingFilters\ListingFilter\MultiSelect\Storefront\Element;
use ITB\ITBConfigurableListingFilters\Twig\MultiSelectFilterElementExtension;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Twig\TwigTest;

#[CoversClass(MultiSelectFilterElementExtension::class)]
final class MultiSelectFilterElementExtensionTest extends TestCase
{
    public static function getTestsProvider(): \Generator
    {
        yield [new MultiSelectFilterElementExtension(), 'instanceof', 'multi_select_filter_element'];
    }

    public static function isMultiSelectFilterElementProvider(): \Generator
    {
        $extension = new MultiSelectFilterElementExtension();

        yield '$var is string' => [$extension, 'string', false];
        yield '$var is int' => [$extension, 1, false];
        yield '$var is object but not MultiSelectFilterElement' => [$extension, new \stdClass(), false];
        yield '$var is MultiSelectFilterElement' => [$extension, new Element('name', 'test'), true];
    }

    #[DataProvider('getTestsProvider')]
    public function testGetTests(MultiSelectFilterElementExtension $extension, string $expectedTestKey, string $expectedTestName): void
    {
        $this->assertCount(1, $extension->getTests());
        $this->assertSame($expectedTestKey, array_key_first($extension->getTests()));

        $test = array_values($extension->getTests())[0];
        $this->assertSame($expectedTestName, $test->getName());
        $this->assertInstanceOf(TwigTest::class, $test);
        $callable = $test->getCallable();
        $this->assertInstanceOf(\Closure::class, $callable);
    }

    #[DataProvider('isMultiSelectFilterElementProvider')]
    public function testIsMultiSelectFilterElement(MultiSelectFilterElementExtension $extension, mixed $var, bool $expectedResult): void
    {
        $this->assertSame($expectedResult, $extension->isMultiSelectFilterElement($var));
    }
}
