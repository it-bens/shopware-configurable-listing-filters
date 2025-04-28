<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\RangeInterval;

use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\ListingFilterConfigurationEntity;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\RangeInterval\Aggregate\Interval\RangeIntervalListingFilterConfigurationIntervalCollection;

class RangeIntervalListingFilterConfigurationEntity extends ListingFilterConfigurationEntity
{
    public const TWIG_TEMPLATE = '@Storefront/storefront/component/listing/filter/filter-multi-select.html.twig';

    protected ?string $elementPrefix = null;

    protected ?string $elementSuffix = null;

    protected ?RangeIntervalListingFilterConfigurationIntervalCollection $intervals = null;

    /**
     * @api
     */
    public function getAggregationName(): string
    {
        return $this->slugifyDalField($this->dalField);
    }

    /**
     * @api
     */
    public function getElementPrefix(): ?string
    {
        return $this->elementPrefix;
    }

    /**
     * @api
     */
    public function getElementSuffix(): ?string
    {
        return $this->elementSuffix;
    }

    public function getFilterName(): string
    {
        return $this->slugifyDalField($this->dalField);
    }

    /**
     * @api
     */
    public function getIntervals(): ?RangeIntervalListingFilterConfigurationIntervalCollection
    {
        return $this->intervals;
    }

    /**
     * @api
     */
    public function setElementPrefix(?string $elementPrefix): void
    {
        $this->elementPrefix = $elementPrefix;
    }

    /**
     * @api
     */
    public function setElementSuffix(?string $elementSuffix): void
    {
        $this->elementSuffix = $elementSuffix;
    }

    /**
     * @api
     */
    public function setIntervals(RangeIntervalListingFilterConfigurationIntervalCollection $intervals): void
    {
        $this->intervals = $intervals;
    }
}
