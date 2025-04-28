<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\RangeInterval\Aggregate\Interval;

use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\RangeInterval\RangeIntervalListingFilterConfigurationEntity;
use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityIdTrait;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\RangeFilter;

class RangeIntervalListingFilterConfigurationIntervalEntity extends Entity
{
    use EntityIdTrait;

    protected ?int $max = null;

    protected ?int $min = null;

    protected int $position;

    protected RangeIntervalListingFilterConfigurationEntity $rangeIntervalListingFilterConfiguration;

    protected string $rangeIntervalListingFilterConfigurationId;

    public function getCountAggregationName(): string
    {
        return $this->rangeIntervalListingFilterConfiguration->getAggregationName() . '_' . $this->getId();
    }

    public function getIdFromCountAggregationName(string $countAggregationName): string
    {
        $prefix = $this->rangeIntervalListingFilterConfiguration->getAggregationName() . '_';

        return str_replace($prefix, '', $countAggregationName);
    }

    /**
     * @api
     */
    public function getMax(): ?int
    {
        return $this->max;
    }

    /**
     * @api
     */
    public function getMin(): ?int
    {
        return $this->min;
    }

    /**
     * @api
     */
    public function getPosition(): int
    {
        return $this->position;
    }

    /**
     * @return array{
     *     gte?: int,
     *     lte?: int
     * }
     */
    public function getRangeForFilter(): array
    {
        $range = [];
        if ($this->min !== null) {
            $range[RangeFilter::GTE] = $this->min;
        }

        if ($this->max !== null) {
            $range[RangeFilter::LTE] = $this->max;
        }

        return $range;
    }

    /**
     * @api
     */
    public function getRangeIntervalListingFilterConfiguration(): RangeIntervalListingFilterConfigurationEntity
    {
        return $this->rangeIntervalListingFilterConfiguration;
    }

    /**
     * @api
     */
    public function getRangeIntervalListingFilterConfigurationId(): string
    {
        return $this->rangeIntervalListingFilterConfigurationId;
    }

    /**
     * @api
     */
    public function setMax(?int $max): void
    {
        $this->max = $max;
    }

    /**
     * @api
     */
    public function setMin(?int $min): void
    {
        $this->min = $min;
    }

    /**
     * @api
     */
    public function setPosition(int $position): void
    {
        $this->position = $position;
    }

    /**
     * @api
     */
    public function setRangeIntervalListingFilterConfiguration(
        RangeIntervalListingFilterConfigurationEntity $rangeIntervalListingFilterConfiguration
    ): void {
        $this->rangeIntervalListingFilterConfiguration = $rangeIntervalListingFilterConfiguration;
    }

    /**
     * @api
     */
    public function setRangeIntervalListingFilterConfigurationId(string $rangeIntervalListingFilterConfigurationId): void
    {
        $this->rangeIntervalListingFilterConfigurationId = $rangeIntervalListingFilterConfigurationId;
    }
}
