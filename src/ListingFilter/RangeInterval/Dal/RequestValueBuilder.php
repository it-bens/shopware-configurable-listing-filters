<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\ListingFilter\RangeInterval\Dal;

use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\RangeInterval\Aggregate\RangeIntervalListingFilterConfigurationIntervalCollection;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\RangeInterval\RangeIntervalListingFilterConfigurationEntity;
use ITB\ITBConfigurableListingFilters\ListingFilter\MultiSelectValueSplitterInterface;
use ITB\ITBConfigurableListingFilters\ListingFilter\ValueFromRequestExtractorInterface;
use Symfony\Component\HttpFoundation\Request;

final class RequestValueBuilder implements RequestValueBuilderInterface
{
    /**
     * @codeCoverageIgnore
     */
    public function __construct(
        private readonly ValueFromRequestExtractorInterface $valueFromRequestExtractor,
        private readonly MultiSelectValueSplitterInterface $multiSelectValueSplitter,
    ) {
    }

    public function buildRequestValue(RangeIntervalListingFilterConfigurationEntity $configurationEntity, Request $request): RequestValue
    {
        $idsAsString = $this->valueFromRequestExtractor->extractValueFromRequest($request, $configurationEntity->getFilterName());
        $ids = $this->multiSelectValueSplitter->splitMultiSelectValue($idsAsString, $configurationEntity);

        $intervalCollection = $configurationEntity->getIntervals();
        if (! $intervalCollection instanceof RangeIntervalListingFilterConfigurationIntervalCollection) {
            throw new \RuntimeException('`intervals` not loaded in `RangeIntervalListingFilterConfigurationEntity`');
        }

        $intervals = [];
        foreach ($ids as $id) {
            $interval = $intervalCollection->get($id);
            if ($interval !== null) {
                $intervals[] = $interval;
            }
        }

        return new RequestValue($intervals);
    }
}
