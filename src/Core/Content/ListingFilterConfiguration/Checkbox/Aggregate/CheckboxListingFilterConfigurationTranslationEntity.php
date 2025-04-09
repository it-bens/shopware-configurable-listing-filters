<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Checkbox\Aggregate;

use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Aggregate\ListingFilterConfigurationTranslation\ListingFilterConfigurationTranslationEntity;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Checkbox\CheckboxListingFilterConfigurationEntity;

class CheckboxListingFilterConfigurationTranslationEntity extends ListingFilterConfigurationTranslationEntity
{
    protected CheckboxListingFilterConfigurationEntity $checkboxListingFilterConfiguration;

    protected string $checkboxListingFilterConfigurationId;

    public function getCheckboxListingFilterConfiguration(): CheckboxListingFilterConfigurationEntity
    {
        return $this->checkboxListingFilterConfiguration;
    }

    public function getCheckboxListingFilterConfigurationId(): string
    {
        return $this->checkboxListingFilterConfigurationId;
    }

    public function setCheckboxListingFilterConfiguration(
        CheckboxListingFilterConfigurationEntity $checkboxListingFilterConfiguration
    ): void {
        $this->checkboxListingFilterConfiguration = $checkboxListingFilterConfiguration;
    }

    public function setCheckboxListingFilterConfigurationId(string $checkboxListingFilterConfigurationId): void
    {
        $this->checkboxListingFilterConfigurationId = $checkboxListingFilterConfigurationId;
    }
}
