<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\ListingFilter\RangeInterval\Storefront;

use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\RangeInterval\RangeIntervalListingFilterConfigurationEntity;
use ITB\ITBConfigurableListingFilters\ListingFilter\MultiSelect\Storefront\RenderData;
use Shopware\Core\Framework\DataAbstractionLayer\Search\AggregationResult\AggregationResultCollection;
use Symfony\Contracts\Translation\TranslatorInterface;

final class RenderDataBuilder implements RenderDataBuilderInterface
{
    public const JS_PLUGIN_SELECTOR = 'itb-listing-filter-multi-select';

    public function __construct(
        private readonly ElementsExtractorInterface $elementsExtractor,
        private readonly TranslatorInterface $translator
    ) {
    }

    public function buildRenderData(
        RangeIntervalListingFilterConfigurationEntity $configurationEntity,
        AggregationResultCollection $aggregationResults
    ): RenderData {
        return new RenderData(
            $configurationEntity->getTwigTemplate(),
            $configurationEntity->getFilterName(),
            $configurationEntity->getDisplayName(),
            self::JS_PLUGIN_SELECTOR,
            iterator_to_array($this->elementsExtractor->extractElementsFromAggregations($configurationEntity, $aggregationResults)),
            $this->translator->trans('listing.disabledFilterTooltip')
        );
    }
}
