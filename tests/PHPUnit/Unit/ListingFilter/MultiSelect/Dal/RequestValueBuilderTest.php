<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Test\PHPUnit\Unit\ListingFilter\MultiSelect\Dal;

use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\ListingFilterConfigurationEntity;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\MultiSelect\MultiSelectListingFilterConfigurationEntity;
use ITB\ITBConfigurableListingFilters\ListingFilter\MultiSelect\Dal\RequestValueBuilder;
use ITB\ITBConfigurableListingFilters\ListingFilter\MultiSelectValueSplitterInterface;
use ITB\ITBConfigurableListingFilters\ListingFilter\ValueFromRequestExtractorInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

#[CoversClass(RequestValueBuilder::class)]
final class RequestValueBuilderTest extends TestCase
{
    public function testBuildRequestValue(): void
    {
        $request = self::createStub(Request::class);

        $valueFromRequestExtractor = $this->createMock(ValueFromRequestExtractorInterface::class);
        $valueFromRequestExtractor->method('extractValueFromRequest')
            ->willReturnCallback(function (Request $requestArgument) use ($request): string {
                $this->assertEquals($request, $requestArgument);

                return 'color_red|color_blue|color_green';
            });

        $configurationEntity = self::createStub(MultiSelectListingFilterConfigurationEntity::class);

        $multiSelectValueSplitter = $this->createMock(MultiSelectValueSplitterInterface::class);
        $multiSelectValueSplitter->method('splitMultiSelectValue')
            ->willReturnCallback(function (string $valuesAsString, ListingFilterConfigurationEntity $configurationEntityArgument) use (
                $configurationEntity
            ): array {
                $this->assertEquals($configurationEntity, $configurationEntityArgument);

                return ['red', 'blue', 'green'];
            });

        $requestValueBuilder = new RequestValueBuilder($valueFromRequestExtractor, $multiSelectValueSplitter);

        $requestValue = $requestValueBuilder->buildRequestValue($configurationEntity, $request);
        $this->assertSame(['red', 'blue', 'green'], $requestValue->values);
    }
}
