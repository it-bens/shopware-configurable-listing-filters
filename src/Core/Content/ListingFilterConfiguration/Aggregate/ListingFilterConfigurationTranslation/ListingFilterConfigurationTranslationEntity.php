<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Aggregate\ListingFilterConfigurationTranslation;

use Shopware\Core\Framework\DataAbstractionLayer\TranslationEntity;

abstract class ListingFilterConfigurationTranslationEntity extends TranslationEntity
{
    protected string $displayName;

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
    public function setDisplayName(string $displayName): void
    {
        $this->displayName = $displayName;
    }
}
