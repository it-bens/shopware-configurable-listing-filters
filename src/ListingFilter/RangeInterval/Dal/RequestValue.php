<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\ListingFilter\RangeInterval\Dal;

use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\RangeInterval\Aggregate\RangeIntervalListingFilterConfigurationIntervalEntity;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\RangeFilter;

final class RequestValue
{
    /**
     * @codeCoverageIgnore
     *
     * @param array<RangeIntervalListingFilterConfigurationIntervalEntity> $intervals
     */
    public function __construct(
        private readonly array $intervals,
    ) {
    }

    public function isFiltered(): bool
    {
        return $this->intervals !== [];
    }

    /**
     * @return array{
     *     gte?: int,
     *     lte?: int
     * }
     */
    public function range(): array
    {
        $gte = null;
        foreach ($this->intervals as $interval) {
            if ($interval->getMin() === null) {
                $gte = null;
                break;
            }

            if ($gte === null || $interval->getMin() < $gte) {
                $gte = $interval->getMin();
            }
        }

        $lte = null;
        foreach ($this->intervals as $interval) {
            if ($interval->getMax() === null) {
                $lte = null;
                break;
            }

            if ($lte === null || $interval->getMax() > $lte) {
                $lte = $interval->getMax();
            }
        }

        $range = [];
        if ($gte !== null) {
            $range[RangeFilter::GTE] = $gte;
        }

        if ($lte !== null) {
            $range[RangeFilter::LTE] = $lte;
        }

        return $range;
    }
}
