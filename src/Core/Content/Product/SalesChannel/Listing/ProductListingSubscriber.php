<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Core\Content\Product\SalesChannel\Listing;

use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\ListingFilterConfigurationCollection;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\ListingFilterConfigurationRepositoryInterface;
use ITB\ITBConfigurableListingFilters\Core\Content\Product\SalesChannel\Listing\Service\FilterCollectionEnricherInterface;
use ITB\ITBConfigurableListingFilters\Core\Content\Product\SalesChannel\Listing\Service\NativeFilterRemoverInterface;
use Shopware\Core\Content\Product\Events\ProductListingCollectFilterEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class ProductListingSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly ListingFilterConfigurationRepositoryInterface $listingFilterConfigurationRepository,
        private readonly FilterCollectionEnricherInterface $filterCollectionEnricher,
        private readonly NativeFilterRemoverInterface $nativeFilterRemover,
    ) {
    }

    public function addConfigurationBasedFilters(ProductListingCollectFilterEvent $event): void
    {
        $context = $event->getSalesChannelContext()
            ->getContext();
        $salesChannelId = $event->getSalesChannelContext()
            ->getSalesChannelId();

        $listingFilterConfigurationCollection = ListingFilterConfigurationCollection::withListingFilterConfigurationRepository(
            $this->listingFilterConfigurationRepository,
            $context,
            $salesChannelId
        );

        $this->filterCollectionEnricher->enrichFilterCollection(
            $listingFilterConfigurationCollection,
            $event->getRequest(),
            $event->getFilters()
        );
    }

    /**
     * @codeCoverageIgnore
     *
     * @return array<class-string, array<array<int, string|int>>>
     */
    public static function getSubscribedEvents(): array
    {
        return [
            ProductListingCollectFilterEvent::class => [['addConfigurationBasedFilters', -20], ['removeNativeFilters', -10]],
        ];
    }

    public function removeNativeFilters(ProductListingCollectFilterEvent $event): void
    {
        $this->nativeFilterRemover->removeNativeFilters($event->getFilters(), $event->getSalesChannelContext()->getSalesChannelId());
    }
}
