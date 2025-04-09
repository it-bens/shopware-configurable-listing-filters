<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Test\PHPUnit\Unit\ListingFilter\Checkbox\Storefront;

use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Checkbox\CheckboxListingFilterConfigurationEntity;
use ITB\ITBConfigurableListingFilters\ListingFilter\Checkbox\Storefront\RenderDataBuilder;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\Translation\TranslatorInterface;

#[CoversClass(RenderDataBuilder::class)]
final class RenderDataBuilderTest extends TestCase
{
    public static function buildRenderDataProvider(): \Generator
    {
        $configurationEntity = new CheckboxListingFilterConfigurationEntity();
        $configurationEntity->setDalField('isCloseout');
        $configurationEntity->setTwigTemplate(CheckboxListingFilterConfigurationEntity::TWIG_TEMPLATE);
        $configurationEntity->setDisplayName('Is Closeout');

        yield [$configurationEntity, 'listing.disabledFilterTooltip'];
    }

    #[DataProvider('buildRenderDataProvider')]
    public function testBuildRenderData(
        CheckboxListingFilterConfigurationEntity $configurationEntity,
        string $expectedMessageToTranslate,
    ): void {
        $translatorMock = $this->createMock(TranslatorInterface::class);
        $translatorMock->method('trans')
            ->willReturnCallback(function (string $message) use ($expectedMessageToTranslate): string {
                $this->assertSame($expectedMessageToTranslate, $message);

                return 'translated disabled filter text';
            });
        $renderDataBuilder = new RenderDataBuilder($translatorMock);

        $renderData = $renderDataBuilder->buildRenderData($configurationEntity);
        $this->assertSame($configurationEntity->getTwigTemplate(), $renderData->twigTemplate);
        $this->assertSame($configurationEntity->getFilterName(), $renderData->toArray()['name']);
        $this->assertSame($configurationEntity->getDisplayName(), $renderData->toArray()['displayName']);
        $this->assertSame(
            'translated disabled filter text',
            $renderData->toArray()['dataPluginSelectorOptions']['snippets']['disabledFilterText']
        );
    }
}
