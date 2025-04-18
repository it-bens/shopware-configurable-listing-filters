<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Test\PHPUnit\Unit\Core\Content\Product\SalesChannel\Listing;

use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Checkbox\CheckboxListingFilterConfigurationCollection;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Checkbox\CheckboxListingFilterConfigurationEntity;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\ListingFilterConfigurationCollection;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\ListingFilterConfigurationRepositoryInterface;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\MultiSelect\MultiSelectListingFilterConfigurationCollection;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\MultiSelect\MultiSelectListingFilterConfigurationEntity;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Range\RangeListingFilterConfigurationCollection;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Range\RangeListingFilterConfigurationEntity;
use ITB\ITBConfigurableListingFilters\Core\Content\Product\SalesChannel\Listing\ProductListingSubscriber;
use ITB\ITBConfigurableListingFilters\Core\Content\Product\SalesChannel\Listing\Service\FilterCollectionEnricherInterface;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Shopware\Core\Content\Product\Events\ProductListingCollectFilterEvent;
use Shopware\Core\Content\Product\SalesChannel\Listing\FilterCollection;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Symfony\Component\HttpFoundation\Request;

final class ProductListingSubscriberTest extends TestCase
{
    public static function addConfigurationBasedFiltersProvider(): \Generator
    {
        $checkboxListingFilterConfigurationEntity = new CheckboxListingFilterConfigurationEntity();
        $checkboxListingFilterConfigurationEntity->setUniqueIdentifier('checkbox');
        $checkboxListingFilterConfigurationEntity->setDalField('checkboxField');
        $checkboxListingFilterConfigurationEntity->setPosition(1);

        $multiSelectListingFilterConfigurationEntity = new MultiSelectListingFilterConfigurationEntity();
        $multiSelectListingFilterConfigurationEntity->setUniqueIdentifier('multiSelect');
        $multiSelectListingFilterConfigurationEntity->setDalField('multiSelectField');
        $multiSelectListingFilterConfigurationEntity->setPosition(2);

        $rangeListingFilterConfigurationEntity = new RangeListingFilterConfigurationEntity();
        $rangeListingFilterConfigurationEntity->setUniqueIdentifier('range');
        $rangeListingFilterConfigurationEntity->setDalField('rangeField');
        $rangeListingFilterConfigurationEntity->setPosition(3);

        yield [
            $checkboxListingFilterConfigurationEntity,
            $multiSelectListingFilterConfigurationEntity,
            $rangeListingFilterConfigurationEntity,
        ];
    }

    #[DataProvider('addConfigurationBasedFiltersProvider')]
    public function testAddConfigurationBasedFilters(
        CheckboxListingFilterConfigurationEntity $checkboxListingFilterConfigurationEntity,
        MultiSelectListingFilterConfigurationEntity $multiSelectListingFilterConfigurationEntity,
        RangeListingFilterConfigurationEntity $rangeListingFilterConfigurationEntity,
    ): void {
        $request = self::createStub(Request::class);
        $filterCollection = $this->createStub(FilterCollection::class);
        $salesChannelContext = $this->createMock(SalesChannelContext::class);

        $checkBoxListingFilterConfigurationCollection = new CheckboxListingFilterConfigurationCollection([
            $checkboxListingFilterConfigurationEntity,
        ]);
        $multiSelectListingFilterConfigurationCollection = new MultiSelectListingFilterConfigurationCollection([
            $multiSelectListingFilterConfigurationEntity,
        ]);
        $rangeListingFilterConfigurationCollection = new RangeListingFilterConfigurationCollection([
            $rangeListingFilterConfigurationEntity,
        ]);

        $listingFilterConfigurationRepository = $this->createMock(ListingFilterConfigurationRepositoryInterface::class);
        $listingFilterConfigurationRepository
            ->method('getCheckboxListingFilterConfigurations')
            ->willReturnCallback(function (SalesChannelContext $salesChannelContextArgument) use (
                $salesChannelContext,
                $checkBoxListingFilterConfigurationCollection
            ): CheckboxListingFilterConfigurationCollection {
                $this->assertSame($salesChannelContext, $salesChannelContextArgument);

                return $checkBoxListingFilterConfigurationCollection;
            });
        $listingFilterConfigurationRepository
            ->method('getMultiSelectListingFilterConfigurations')
            ->willReturnCallback(function (SalesChannelContext $salesChannelContextArgument) use (
                $salesChannelContext,
                $multiSelectListingFilterConfigurationCollection
            ): MultiSelectListingFilterConfigurationCollection {
                $this->assertSame($salesChannelContext, $salesChannelContextArgument);

                return $multiSelectListingFilterConfigurationCollection;
            });
        $listingFilterConfigurationRepository
            ->method('getRangeListingFilterConfigurations')
            ->willReturnCallback(function (SalesChannelContext $salesChannelContextArgument) use (
                $salesChannelContext,
                $rangeListingFilterConfigurationCollection
            ): RangeListingFilterConfigurationCollection {
                $this->assertSame($salesChannelContext, $salesChannelContextArgument);

                return $rangeListingFilterConfigurationCollection;
            });

        $filterCollectionEnricher = $this->createMock(FilterCollectionEnricherInterface::class);
        $filterCollectionEnricher->method('enrichFilterCollection')
            ->willReturnCallback(
                function (
                    ListingFilterConfigurationCollection $listingFilterConfigurationCollectionArgument,
                    Request $requestArgument,
                    FilterCollection $filterCollectionArgument
                ) use (
                    $checkboxListingFilterConfigurationEntity,
                    $multiSelectListingFilterConfigurationEntity,
                    $rangeListingFilterConfigurationEntity,
                    $request,
                    $filterCollection
                ) {
                    $this->assertEquals(
                        [
                            $checkboxListingFilterConfigurationEntity,
                            $multiSelectListingFilterConfigurationEntity,
                            $rangeListingFilterConfigurationEntity,
                        ],
                        $listingFilterConfigurationCollectionArgument->getListingFilterConfigurations()
                    );

                    $this->assertSame($request, $requestArgument);
                    $this->assertSame($filterCollection, $filterCollectionArgument);

                    return null;
                }
            );

        $productListingSubscriber = new ProductListingSubscriber($listingFilterConfigurationRepository, $filterCollectionEnricher);

        $event = new ProductListingCollectFilterEvent($request, $filterCollection, $salesChannelContext);

        $productListingSubscriber->addConfigurationBasedFilters($event);
    }
}
