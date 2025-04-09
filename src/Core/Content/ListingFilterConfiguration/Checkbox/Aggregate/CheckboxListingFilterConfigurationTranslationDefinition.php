<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Checkbox\Aggregate;

use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Aggregate\ListingFilterConfigurationTranslation\ListingFilterConfigurationTranslationDefinition;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Checkbox\CheckboxListingFilterConfigurationDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Entity;

class CheckboxListingFilterConfigurationTranslationDefinition extends ListingFilterConfigurationTranslationDefinition
{
    public const ENTITY_NAME = 'itb_listing_filter_configuration_checkbox_translation';

    public function getCollectionClass(): string
    {
        return CheckboxListingFilterConfigurationTranslationCollection::class;
    }

    /**
     * @return class-string<Entity>
     */
    public function getEntityClass(): string
    {
        return CheckboxListingFilterConfigurationTranslationEntity::class;
    }

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    protected function getParentDefinitionClass(): string
    {
        return CheckboxListingFilterConfigurationDefinition::class;
    }
}
