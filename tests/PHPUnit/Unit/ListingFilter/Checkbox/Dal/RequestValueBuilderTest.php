<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Test\PHPUnit\Unit\ListingFilter\Checkbox\Dal;

use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Checkbox\CheckboxListingFilterConfigurationEntity;
use ITB\ITBConfigurableListingFilters\ListingFilter\Checkbox\Dal\RequestValueBuilder;
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
        $requestValueBuilder = new RequestValueBuilder();

        $configurationEntity = new CheckboxListingFilterConfigurationEntity();
        $configurationEntity->setDalField('isCloseout');

        $requestStub = self::createStub(Request::class);
        $inputBag = new InputBag([
            'is-closeout' => '1',
        ]);
        $requestStub->query = $inputBag;
        $requestStub->request = $inputBag;
        yield 'request value = "1"' => [$requestValueBuilder, $configurationEntity, $requestStub, true];

        $requestStub = self::createStub(Request::class);
        $inputBag = new InputBag([
            'is-closeout' => '0',
        ]);
        $requestStub->query = $inputBag;
        $requestStub->request = $inputBag;
        yield 'request value = "0"' => [$requestValueBuilder, $configurationEntity, $requestStub, false];

        $requestStub = self::createStub(Request::class);
        $inputBag = new InputBag([
            'is-closeout' => '',
        ]);
        $requestStub->query = $inputBag;
        $requestStub->request = $inputBag;
        yield 'request value = ""' => [$requestValueBuilder, $configurationEntity, $requestStub, false];

        $requestStub = self::createStub(Request::class);
        $inputBag = new InputBag([
            'is-closeout' => 'test',
        ]);
        $requestStub->query = $inputBag;
        $requestStub->request = $inputBag;
        yield 'request value = "test"' => [$requestValueBuilder, $configurationEntity, $requestStub, false];
    }

    #[DataProvider('buildRequestValueProvider')]
    public function testBuildRequestValue(
        RequestValueBuilder $requestValueBuilder,
        CheckboxListingFilterConfigurationEntity $configurationEntity,
        Request $request,
        bool $expectedValue,
    ): void {
        $requestValue = $requestValueBuilder->buildRequestValue($configurationEntity, $request);
        $this->assertSame($expectedValue, $requestValue->value);
    }
}
