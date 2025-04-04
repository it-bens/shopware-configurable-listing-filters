<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Range\Aggregate;

use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Aggregate\ListingFilterConfigurationTranslation\ListingFilterConfigurationTranslationEntity;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Range\RangeListingFilterConfigurationEntity;

final class RangeListingFilterConfigurationTranslationEntity extends ListingFilterConfigurationTranslationEntity
{
    protected RangeListingFilterConfigurationEntity $rangeListingFilterConfiguration;

    protected string $rangeListingFilterConfigurationId;

    protected ?string $unit = null;

    /**
     * @api
     */
    public function getRangeListingFilterConfiguration(): RangeListingFilterConfigurationEntity
    {
        return $this->rangeListingFilterConfiguration;
    }

    /**
     * @api
     */
    public function getRangeListingFilterConfigurationId(): string
    {
        return $this->rangeListingFilterConfigurationId;
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
    public function setRangeListingFilterConfiguration(RangeListingFilterConfigurationEntity $rangeListingFilterConfiguration): void
    {
        $this->rangeListingFilterConfiguration = $rangeListingFilterConfiguration;
    }

    /**
     * @api
     */
    public function setRangeListingFilterConfigurationId(string $rangeListingFilterConfigurationId): void
    {
        $this->rangeListingFilterConfigurationId = $rangeListingFilterConfigurationId;
    }

    /**
     * @api
     */
    public function setUnit(?string $unit): void
    {
        $this->unit = $unit;
    }
}
