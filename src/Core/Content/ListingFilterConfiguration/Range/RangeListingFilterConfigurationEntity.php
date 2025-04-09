<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Range;

use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\ListingFilterConfigurationEntity;

class RangeListingFilterConfigurationEntity extends ListingFilterConfigurationEntity
{
    public const TWIG_TEMPLATE = '@Storefront/storefront/component/listing/filter/filter-range.html.twig';

    protected ?string $unit = null;

    public function getFilterName(): string
    {
        return $this->slugifyDalField($this->dalField);
    }

    public function getMaximalValueAggregationName(): string
    {
        $dalFieldParts = explode('.', $this->dalField);
        $lastPart = array_pop($dalFieldParts);
        $lastPart = 'max_' . $lastPart;
        $dalFieldParts[] = $lastPart;

        return $this->slugifyDalField(implode('.', $dalFieldParts));
    }

    public function getMinimalValueAggregationName(): string
    {
        $dalFieldParts = explode('.', $this->dalField);
        $lastPart = array_pop($dalFieldParts);
        $lastPart = 'min_' . $lastPart;
        $dalFieldParts[] = $lastPart;

        return $this->slugifyDalField(implode('.', $dalFieldParts));
    }

    /**
     * @api
     */
    public function getUnit(): ?string
    {
        return $this->unit;
    }

    /**
     * @api
     */
    public function setUnit(?string $unit): void
    {
        $this->unit = $unit;
    }
}
