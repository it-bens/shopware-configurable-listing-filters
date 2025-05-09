<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Core\Content\Cms;

use ITB\ITBConfigurableListingFilters\Core\Content\Cms\Service\ProductListingAggregationsExtractorInterface;
use ITB\ITBConfigurableListingFilters\Core\Content\Cms\Service\RenderDataCollectionBuilderInterface;
use ITB\ITBConfigurableListingFilters\Core\Content\Cms\Service\SidebarFilterCmsSlotsExtractorInterface;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\ListingFilterConfigurationCollection;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\ListingFilterConfigurationRepositoryInterface;
use Shopware\Core\Content\Cms\Events\CmsPageLoadedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class ProductListingPageSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly SidebarFilterCmsSlotsExtractorInterface $sidebarFilterCmsSlotsExtractor,
        private readonly ProductListingAggregationsExtractorInterface $productListingAggregationsExtractor,
        private readonly ListingFilterConfigurationRepositoryInterface $listingFilterConfigurationRepository,
        private readonly RenderDataCollectionBuilderInterface $renderDataCollectionBuilder,
    ) {
    }

    public function addStorefrontFilters(CmsPageLoadedEvent $event): void
    {
        $cmsPageCollection = $event->getResult();
        $sidebarFilterCmsSlots = $this->sidebarFilterCmsSlotsExtractor->extractSidebarFilterCmsSlots($cmsPageCollection);
        if ($sidebarFilterCmsSlots === []) {
            return;
        }

        $aggregationResults = $this->productListingAggregationsExtractor->extractProductListingAggregations($cmsPageCollection);

        $context = $event->getSalesChannelContext()
            ->getContext();
        $salesChannelId = $event->getSalesChannelContext()
            ->getSalesChannelId();

        $listingFilterConfigurationCollection = new ListingFilterConfigurationCollection(
            $this->listingFilterConfigurationRepository->getCheckboxListingFilterConfigurations($context, $salesChannelId),
            $this->listingFilterConfigurationRepository->getMultiSelectListingFilterConfigurations($context, $salesChannelId),
            $this->listingFilterConfigurationRepository->getRangeListingFilterConfigurations($context, $salesChannelId),
            $this->listingFilterConfigurationRepository->getRangeIntervalListingFilterConfigurations($context, $salesChannelId),
        );

        $renderDataCollection = $this->renderDataCollectionBuilder->buildRenderDataCollection(
            $listingFilterConfigurationCollection,
            $aggregationResults
        );
        foreach ($sidebarFilterCmsSlots as $cmsSlot) {
            $cmsSlot->addExtension(RenderDataCollection::NAME, $renderDataCollection);
        }
    }

    /**
     * @codeCoverageIgnore
     *
     * @return array<string, string>
     */
    public static function getSubscribedEvents(): array
    {
        return [
            CmsPageLoadedEvent::class => 'addStorefrontFilters',
        ];
    }
}
