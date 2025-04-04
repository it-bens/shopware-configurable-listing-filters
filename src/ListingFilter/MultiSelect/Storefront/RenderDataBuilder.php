<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\ListingFilter\MultiSelect\Storefront;

use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\MultiSelect\MultiSelectListingFilterConfigurationEntity;
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
        MultiSelectListingFilterConfigurationEntity $configurationEntity,
        AggregationResultCollection $aggregationResults
    ): RenderData {
        return new RenderData(
            $configurationEntity->getTwigTemplate(),
            $configurationEntity->getFilterName(),
            $configurationEntity->getDisplayName(),
            self::JS_PLUGIN_SELECTOR,
            $this->elementsExtractor->extractElementsFromAggregations($configurationEntity, $aggregationResults),
            $this->translator->trans('listing.disabledFilterTooltip')
        );
    }
}
