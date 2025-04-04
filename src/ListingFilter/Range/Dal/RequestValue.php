<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\ListingFilter\Range\Dal;

use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\RangeFilter;

final class RequestValue
{
    public function __construct(
        private readonly ?int $gte,
        private readonly ?int $lte
    ) {
    }

    public function isFiltered(): bool
    {
        return $this->gte !== null || $this->lte !== null;
    }

    /**
     * @return array{
     *     gte?: int,
     *     lte?: int
     * }
     */
    public function range(): array
    {
        $range = [];
        if ($this->gte !== null) {
            $range[RangeFilter::GTE] = $this->gte;
        }

        if ($this->lte !== null) {
            $range[RangeFilter::LTE] = $this->lte;
        }

        return $range;
    }
}
