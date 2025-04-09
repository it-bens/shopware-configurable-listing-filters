<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Test\PHPUnit\Unit\ListingFilter\MultiSelect\Dal;

use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\MultiSelect\MultiSelectListingFilterConfigurationEntity;
use ITB\ITBConfigurableListingFilters\ListingFilter\MultiSelect\Dal\RequestValueBuilder;
use ITB\ITBConfigurableListingFilters\ListingFilter\MultiSelect\Dal\ValueFromRequestExtractor;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\InputBag;
use Symfony\Component\HttpFoundation\Request;

#[CoversClass(RequestValueBuilder::class)]
final class RequestValueBuilderTest extends TestCase
{
    public static function buildRequestValueProvider(): \Generator
    {
        $requestValueBuilder = new RequestValueBuilder(new ValueFromRequestExtractor());

        $configurationEntity = new MultiSelectListingFilterConfigurationEntity();
        $configurationEntity->setDalField('color');

        $requestStub = self::createStub(Request::class);
        $inputBag = new InputBag([
            'color' => 'color_red|color_blue|color_green',
        ]);
        $requestStub->query = $inputBag;
        $requestStub->request = $inputBag;
        yield 'request with multiple values' => [$requestValueBuilder, $configurationEntity, $requestStub, ['red', 'blue', 'green']];

        $requestStub = self::createStub(Request::class);
        $inputBag = new InputBag([
            'color' => 'color_red',
        ]);
        $requestStub->query = $inputBag;
        $requestStub->request = $inputBag;
        yield 'request with single value' => [$requestValueBuilder, $configurationEntity, $requestStub, ['red']];

        $requestStub = self::createStub(Request::class);
        $inputBag = new InputBag([
            'color' => '',
        ]);
        $requestStub->query = $inputBag;
        $requestStub->request = $inputBag;
        yield 'request with empty string' => [$requestValueBuilder, $configurationEntity, $requestStub, []];

        $requestStub = self::createStub(Request::class);
        $inputBag = new InputBag([
            'color' => '|||',
        ]);
        $requestStub->query = $inputBag;
        $requestStub->request = $inputBag;
        yield 'request with only separators' => [$requestValueBuilder, $configurationEntity, $requestStub, []];
    }

    /**
     * @param array<string> $expectedValues
     */
    #[DataProvider('buildRequestValueProvider')]
    public function testBuildRequestValue(
        RequestValueBuilder $requestValueBuilder,
        MultiSelectListingFilterConfigurationEntity $configurationEntity,
        Request $request,
        array $expectedValues,
    ): void {
        $requestValue = $requestValueBuilder->buildRequestValue($configurationEntity, $request);
        $this->assertEquals($expectedValues, $requestValue->values);
    }
}
