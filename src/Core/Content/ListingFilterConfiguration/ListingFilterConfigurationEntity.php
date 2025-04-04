<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration;

use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityIdTrait;
use Shopware\Core\System\SalesChannel\SalesChannelEntity;

abstract class ListingFilterConfigurationEntity extends Entity
{
    use EntityIdTrait;

    protected string $dalField;

    protected string $displayName;

    protected bool $enabled;

    protected ?int $position = null;

    protected ?SalesChannelEntity $salesChannelEntity = null;

    protected ?string $salesChannelId = null;

    protected string $twigTemplate;

    protected string $uniqueName;

    /**
     * @api
     */
    public function getDalField(): string
    {
        return $this->dalField;
    }

    /**
     * @api
     */
    public function getDisplayName(): string
    {
        return $this->displayName;
    }

    /**
     * @api
     */
    public function getEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * @api
     */
    abstract public function getFilterName(): string;

    public function getFullyQualifiedDalField(): string
    {
        return 'product.' . $this->dalField;
    }

    /**
     * @api
     */
    public function getPosition(): ?int
    {
        return $this->position;
    }

    /**
     * @api
     */
    public function getSalesChannelEntity(): ?SalesChannelEntity
    {
        return $this->salesChannelEntity;
    }

    /**
     * @api
     */
    public function getSalesChannelId(): ?string
    {
        return $this->salesChannelId;
    }

    /**
     * @api
     */
    public function getTwigTemplate(): string
    {
        return $this->twigTemplate;
    }

    /**
     * @api
     */
    public function getUniqueName(): string
    {
        return $this->uniqueName;
    }

    /**
     * @api
     */
    public function setDalField(string $dalField): void
    {
        $this->dalField = $dalField;
    }

    /**
     * @api
     */
    public function setDisplayName(string $displayName): void
    {
        $this->displayName = $displayName;
    }

    /**
     * @api
     */
    public function setEnabled(bool $enabled): void
    {
        $this->enabled = $enabled;
    }

    /**
     * @api
     */
    public function setPosition(?int $position): void
    {
        $this->position = $position;
    }

    /**
     * @api
     */
    public function setSalesChannelEntity(?SalesChannelEntity $salesChannelEntity): void
    {
        $this->salesChannelEntity = $salesChannelEntity;
    }

    /**
     * @api
     */
    public function setSalesChannelId(?string $salesChannelId): void
    {
        $this->salesChannelId = $salesChannelId;
    }

    /**
     * @api
     */
    public function setTwigTemplate(string $twigTemplate): void
    {
        $this->twigTemplate = $twigTemplate;
    }

    /**
     * @api
     */
    public function setUniqueName(string $uniqueName): void
    {
        $this->uniqueName = $uniqueName;
    }

    protected function slugifyDalField(string $dalField): string
    {
        $snakeCasedDalField = strtolower((string) preg_replace('/[A-Z]/', '_\\0', lcfirst($dalField)));

        return str_replace(['.', '_'], '-', $snakeCasedDalField);
    }
}
