<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\ListingFilter\Range\Storefront;

use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Range\RangeListingFilterConfigurationEntity;
use Shopware\Core\Framework\DataAbstractionLayer\Search\AggregationResult\AggregationResultCollection;
use Symfony\Contracts\Translation\TranslatorInterface;

final class RenderDataBuilder implements RenderDataBuilderInterface
{
    /**
     * @codeCoverageIgnore
     */
    public function __construct(
        private readonly InputValueExtractorInterface $inputValueExtractor,
        private readonly TranslatorInterface $translator
    ) {
    }

    public function buildRenderData(
        RangeListingFilterConfigurationEntity $configurationEntity,
        AggregationResultCollection $aggregationResults
    ): RenderData {
        $gteInputValue = $this->inputValueExtractor->extractGteInputValueFromAggregations($configurationEntity, $aggregationResults);
        $lteInputValue = $this->inputValueExtractor->extractLteInputValueFromAggregations($configurationEntity, $aggregationResults);

        return new RenderData(
            $configurationEntity->getTwigTemplate(),
            $configurationEntity->getFilterName(),
            $configurationEntity->getDisplayName(),
            $configurationEntity->getMinimalValueAggregationName(),
            $configurationEntity->getMaximalValueAggregationName(),
            $gteInputValue,
            $lteInputValue,
            $configurationEntity->getUnit(),
            $this->translator->trans('listing.disabledFilterTooltip')
        );
    }
}
