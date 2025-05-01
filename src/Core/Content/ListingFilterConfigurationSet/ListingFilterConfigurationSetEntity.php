<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfigurationSet;

use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Checkbox\CheckboxListingFilterConfigurationCollection;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\MultiSelect\MultiSelectListingFilterConfigurationCollection;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Range\RangeListingFilterConfigurationCollection;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\RangeInterval\RangeIntervalListingFilterConfigurationCollection;
use Shopware\Core\Content\Category\CategoryCollection;
use Shopware\Core\Framework\DataAbstractionLayer\Entity;

class ListingFilterConfigurationSetEntity extends Entity
{
    protected ?CategoryCollection $categories = null;

    protected ?CategoryCollection $categoriesRo = null;

    /**
     * @var array<string>|null
     */
    protected ?array $categoryTree = null;

    /**
     * @var array<string>|null
     */
    protected ?array $checkboxConfigurationIds = null;

    protected ?CheckboxListingFilterConfigurationCollection $checkboxConfigurations = null;

    protected string $id;

    /**
     * @var array<string>|null
     */
    protected ?array $multiSelectListingConfigurationIds = null;

    protected ?MultiSelectListingFilterConfigurationCollection $multiSelectListingConfigurations = null;

    /**
     * @var array<string>|null
     */
    protected ?array $rangeIntervalListingConfigurationIds = null;

    protected ?RangeIntervalListingFilterConfigurationCollection $rangeIntervalListingConfigurations = null;

    /**
     * @var array<string>|null
     */
    protected ?array $rangeListingConfigurationIds = null;

    protected ?RangeListingFilterConfigurationCollection $rangeListingConfigurations = null;

    protected string $technicalName;

    /**
     * @api
     */
    public function getCategories(): ?CategoryCollection
    {
        return $this->categories;
    }

    /**
     * @api
     */
    public function getCategoriesRo(): ?CategoryCollection
    {
        return $this->categoriesRo;
    }

    /**
     * @api
     *
     * @return array<string>|null
     */
    public function getCategoryTree(): ?array
    {
        return $this->categoryTree;
    }

    /**
     * @api
     */
    public function getCheckboxConfigurationIds(): ?array
    {
        return $this->checkboxConfigurationIds;
    }

    /**
     * @api
     */
    public function getCheckboxConfigurations(): ?CheckboxListingFilterConfigurationCollection
    {
        return $this->checkboxConfigurations;
    }

    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @api
     *
     * @return array<string>|null
     */
    public function getMultiSelectListingConfigurationIds(): ?array
    {
        return $this->multiSelectListingConfigurationIds;
    }

    /**
     * @api
     */
    public function getMultiSelectListingConfigurations(): ?MultiSelectListingFilterConfigurationCollection
    {
        return $this->multiSelectListingConfigurations;
    }

    /**
     * @api
     *
     * @return array<string>|null
     */
    public function getRangeIntervalListingConfigurationIds(): ?array
    {
        return $this->rangeIntervalListingConfigurationIds;
    }

    /**
     * @api
     */
    public function getRangeIntervalListingConfigurations(): ?RangeIntervalListingFilterConfigurationCollection
    {
        return $this->rangeIntervalListingConfigurations;
    }

    /**
     * @api
     *
     * @return array<string>|null
     */
    public function getRangeListingConfigurationIds(): ?array
    {
        return $this->rangeListingConfigurationIds;
    }

    /**
     * @api
     */
    public function getRangeListingConfigurations(): ?RangeListingFilterConfigurationCollection
    {
        return $this->rangeListingConfigurations;
    }

    public function getTechnicalName(): string
    {
        return $this->technicalName;
    }

    /**
     * @api
     */
    public function setCategories(?CategoryCollection $categories): void
    {
        $this->categories = $categories;
    }

    /**
     * @api
     */
    public function setCategoriesRo(?CategoryCollection $categoriesRo): void
    {
        $this->categoriesRo = $categoriesRo;
    }

    /**
     * @api
     *
     * @param array<string>|null $categoryTree
     */
    public function setCategoryTree(?array $categoryTree): void
    {
        $this->categoryTree = $categoryTree;
    }

    /**
     * @api
     */
    public function setCheckboxConfigurationIds(?array $checkboxConfigurationIds): void
    {
        $this->checkboxConfigurationIds = $checkboxConfigurationIds;
    }

    /**
     * @api
     */
    public function setCheckboxConfigurations(?CheckboxListingFilterConfigurationCollection $checkboxConfigurations): void
    {
        $this->checkboxConfigurations = $checkboxConfigurations;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    /**
     * @api
     *
     * @param array<string>|null $multiSelectListingConfigurationIds
     */
    public function setMultiSelectListingConfigurationIds(?array $multiSelectListingConfigurationIds): void
    {
        $this->multiSelectListingConfigurationIds = $multiSelectListingConfigurationIds;
    }

    /**
     * @api
     */
    public function setMultiSelectListingConfigurations(
        ?MultiSelectListingFilterConfigurationCollection $multiSelectListingConfigurations
    ): void {
        $this->multiSelectListingConfigurations = $multiSelectListingConfigurations;
    }

    /**
     * @api
     *
     * @param array<string>|null $rangeIntervalListingConfigurationIds
     */
    public function setRangeIntervalListingConfigurationIds(?array $rangeIntervalListingConfigurationIds): void
    {
        $this->rangeIntervalListingConfigurationIds = $rangeIntervalListingConfigurationIds;
    }

    /**
     * @api
     */
    public function setRangeIntervalListingConfigurations(
        ?RangeIntervalListingFilterConfigurationCollection $rangeIntervalListingConfigurations
    ): void {
        $this->rangeIntervalListingConfigurations = $rangeIntervalListingConfigurations;
    }

    /**
     * @api
     *
     * @param array<string>|null $rangeListingConfigurationIds
     */
    public function setRangeListingConfigurationIds(?array $rangeListingConfigurationIds): void
    {
        $this->rangeListingConfigurationIds = $rangeListingConfigurationIds;
    }

    /**
     * @api
     */
    public function setRangeListingConfigurations(?RangeListingFilterConfigurationCollection $rangeListingConfigurations): void
    {
        $this->rangeListingConfigurations = $rangeListingConfigurations;
    }

    /**
     * @api
     */
    public function setTechnicalName(string $technicalName): void
    {
        $this->technicalName = $technicalName;
    }
}
