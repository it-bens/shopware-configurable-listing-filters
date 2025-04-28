<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\RangeInterval\Aggregate\Translation;

use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Aggregate\ListingFilterConfigurationTranslation\ListingFilterConfigurationTranslationEntity;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\RangeInterval\RangeIntervalListingFilterConfigurationEntity;

class RangeIntervalListingFilterConfigurationTranslationEntity extends ListingFilterConfigurationTranslationEntity
{
    protected ?string $elementPrefix = null;

    protected ?string $elementSuffix = null;

    protected RangeIntervalListingFilterConfigurationEntity $rangeIntervalListingFilterConfiguration;

    protected string $rangeIntervalListingFilterConfigurationId;

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
