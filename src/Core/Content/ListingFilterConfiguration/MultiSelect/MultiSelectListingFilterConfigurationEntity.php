<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\MultiSelect;

use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\ListingFilterConfigurationEntity;

class MultiSelectListingFilterConfigurationEntity extends ListingFilterConfigurationEntity
{
    public const TWIG_TEMPLATE = '@Storefront/storefront/component/listing/filter/filter-multi-select.html.twig';

    /**
     * @var array<string>|null
     */
    protected ?array $additionalDalFields = null;

    /**
     * @return array<string>|null
     */
    public function getAdditionalDalFields(): ?array
    {
        return $this->additionalDalFields;
    }

    /**
     * @param array<string>|null $additionalDalFields
     */
    public function setAdditionalDalFields(?array $additionalDalFields): void
    {
        $this->additionalDalFields = $additionalDalFields;
    }

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

    protected ?string $sortingOrder = null;

    /**
     * @api
     */
    public function getAggregationName(): string
    {
        return $this->slugifyDalField($this->dalField);
    }

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

    public function getFilterName(): string
    {
        return $this->slugifyDalField($this->dalField);
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
    public function getSortingOrder(): ?string
    {
        return $this->sortingOrder;
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
    public function setSortingOrder(?string $sortingOrder): void
    {
        $this->sortingOrder = $sortingOrder;
    }
}
