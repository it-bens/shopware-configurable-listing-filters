<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Core\Content\Cms\Service;

use ITB\ITBConfigurableListingFilters\Core\Content\Cms\RenderDataCollection;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Checkbox\CheckboxListingFilterConfigurationEntity;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\ListingFilterConfigurationCollection;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\MultiSelect\MultiSelectListingFilterConfigurationEntity;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Range\RangeListingFilterConfigurationEntity;
use ITB\ITBConfigurableListingFilters\ListingFilter\Checkbox\Storefront\RenderDataBuilderInterface as CheckboxRenderDataBuilder;
use ITB\ITBConfigurableListingFilters\ListingFilter\MultiSelect\Storefront\RenderDataBuilderInterface as MultiSelectRenderDataBuilder;
use ITB\ITBConfigurableListingFilters\ListingFilter\Range\Storefront\RenderDataBuilderInterface as RangeRenderDataBuilder;
use Shopware\Core\Framework\DataAbstractionLayer\Search\AggregationResult\AggregationResultCollection;

final class RenderDataCollectionBuilder implements RenderDataCollectionBuilderInterface
{
    public function __construct(
        private readonly CheckboxRenderDataBuilder $checkboxRenderDataBuilder,
        private readonly MultiSelectRenderDataBuilder $multiSelectRenderDataBuilder,
        private readonly RangeRenderDataBuilder $rangeRenderDataBuilder,
    ) {
    }

    public function buildRenderDataCollection(
        ListingFilterConfigurationCollection $listingFilterConfigurationCollection,
        AggregationResultCollection $aggregationResults,
    ): RenderDataCollection {
        $renderDataCollection = new RenderDataCollection();
        foreach ($listingFilterConfigurationCollection as $listingFilterConfigurationEntity) {
            if ($listingFilterConfigurationEntity->getEnabled() === false) {
                continue;
            }

            switch ($listingFilterConfigurationEntity::class) {
                case CheckboxListingFilterConfigurationEntity::class:
                    $renderDataCollection->add($this->checkboxRenderDataBuilder->buildRenderData($listingFilterConfigurationEntity));
                    break;
                case MultiSelectListingFilterConfigurationEntity::class:
                    $renderDataCollection->add(
                        $this->multiSelectRenderDataBuilder->buildRenderData($listingFilterConfigurationEntity, $aggregationResults)
                    );
                    break;
                case RangeListingFilterConfigurationEntity::class:
                    $renderDataCollection->add(
                        $this->rangeRenderDataBuilder->buildRenderData($listingFilterConfigurationEntity, $aggregationResults)
                    );
                    break;
            }
        }

        return $renderDataCollection;
    }
}
