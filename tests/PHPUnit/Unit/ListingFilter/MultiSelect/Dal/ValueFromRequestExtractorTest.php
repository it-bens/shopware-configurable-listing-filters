<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Test\PHPUnit\Unit\ListingFilter\MultiSelect\Dal;

use ITB\ITBConfigurableListingFilters\ListingFilter\MultiSelect\Dal\ValueFromRequestExtractor;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;

#[CoversClass(ValueFromRequestExtractor::class)]
final class ValueFromRequestExtractorTest extends TestCase
{
    public static function extractValueFromRequestProvider(): \Generator
    {
        $request = self::createStub(Request::class);
        $request->method('isMethod')
            ->willReturn(false);
        $queryBag = self::createStub(ParameterBag::class);
        $queryBag->method('get')
            ->willReturn('value');
        $request->query = $queryBag;
        $requestBag = self::createStub(ParameterBag::class);
        $requestBag->method('get')
            ->willThrowException(new \RuntimeException('Should not be called'));
        $request->request = $requestBag;

        yield 'GET request' => [new ValueFromRequestExtractor(), $request, 'key', 'value'];

        $request = self::createStub(Request::class);
        $request->method('isMethod')
            ->willReturn(true);
        $requestBag = self::createStub(ParameterBag::class);
        $requestBag->method('get')
            ->willReturn('value');
        $request->request = $requestBag;
        $queryBag = self::createStub(ParameterBag::class);
        $queryBag->method('get')
            ->willThrowException(new \RuntimeException('Should not be called'));
        $request->query = $queryBag;

        yield 'POST request' => [new ValueFromRequestExtractor(), $request, 'key', 'value'];
    }

    #[DataProvider('extractValueFromRequestProvider')]
    public function testExtractValueFromRequest(
        ValueFromRequestExtractor $valueFromRequestExtractor,
        Request $request,
        string $key,
        string $expectedValue,
    ): void {
        $value = $valueFromRequestExtractor->extractValueFromRequest($request, $key);
        $this->assertSame($expectedValue, $value);
    }
}
