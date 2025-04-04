<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\MultiSelect\Aggregate;

use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Aggregate\ListingFilterConfigurationTranslation\ListingFilterConfigurationTranslationEntity;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\MultiSelect\MultiSelectListingFilterConfigurationEntity;

final class MultiSelectListingFilterConfigurationTranslationEntity extends ListingFilterConfigurationTranslationEntity
{
    /**
     * @var array<string>|null
     */
    protected ?array $allowedElements = null;

    protected ?string $elementPrefix = null;

    protected ?string $elementSuffix = null;

    /**
     * @var array<string>|null
     */
    protected ?array $explicitElementSorting = null;

    /**
     * @var array<string>|null
     */
    protected ?array $forbiddenElements = null;

    protected MultiSelectListingFilterConfigurationEntity $multiSelectListingFilterConfiguration;

    protected string $multiSelectListingFilterConfigurationId;

    /**
     * @api
     *
     * @return array<string>|null
     */
    public function getAllowedElements(): ?array
    {
        return $this->allowedElements;
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

    /**
     * @api
     *
     * @return array<string>|null
     */
    public function getExplicitElementSorting(): ?array
    {
        return $this->explicitElementSorting;
    }

    /**
     * @api
     *
     * @return array<string>|null
     */
    public function getForbiddenElements(): ?array
    {
        return $this->forbiddenElements;
    }

    /**
     * @api
     */
    public function getMultiSelectListingFilterConfiguration(): MultiSelectListingFilterConfigurationEntity
    {
        return $this->multiSelectListingFilterConfiguration;
    }

    /**
     * @api
     */
    public function getMultiSelectListingFilterConfigurationId(): string
    {
        return $this->multiSelectListingFilterConfigurationId;
    }

    /**
     * @api
     *
     * @param array<string>|null $allowedElements
     */
    public function setAllowedElements(?array $allowedElements): void
    {
        $this->allowedElements = $allowedElements;
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
     *
     * @param array<string>|null $explicitElementSorting
     */
    public function setExplicitElementSorting(?array $explicitElementSorting): void
    {
        $this->explicitElementSorting = $explicitElementSorting;
    }

    /**
     * @api
     *
     * @param array<string>|null $forbiddenElements
     */
    public function setForbiddenElements(?array $forbiddenElements): void
    {
        $this->forbiddenElements = $forbiddenElements;
    }

    /**
     * @api
     */
    public function setMultiSelectListingFilterConfiguration(
        MultiSelectListingFilterConfigurationEntity $multiSelectListingFilterConfiguration
    ): void {
        $this->multiSelectListingFilterConfiguration = $multiSelectListingFilterConfiguration;
    }

    /**
     * @api
     */
    public function setMultiSelectListingFilterConfigurationId(string $multiSelectListingFilterConfigurationId): void
    {
        $this->multiSelectListingFilterConfigurationId = $multiSelectListingFilterConfigurationId;
    }
}
