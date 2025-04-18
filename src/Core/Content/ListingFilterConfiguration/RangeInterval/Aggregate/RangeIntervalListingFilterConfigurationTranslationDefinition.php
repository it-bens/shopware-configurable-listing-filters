<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\RangeInterval\Aggregate;

use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Aggregate\ListingFilterConfigurationTranslation\ListingFilterConfigurationTranslationDefinition;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\RangeInterval\RangeIntervalListingFilterConfigurationDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\ApiAware;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;

class RangeIntervalListingFilterConfigurationTranslationDefinition extends ListingFilterConfigurationTranslationDefinition
{
    public const ENTITY_NAME = 'itb_listing_filter_configuration_range_interval_translation';

    public function getCollectionClass(): string
    {
        return RangeIntervalListingFilterConfigurationTranslationCollection::class;
    }

    /**
     * @return class-string<Entity>
     */
    public function getEntityClass(): string
    {
        return RangeIntervalListingFilterConfigurationTranslationEntity::class;
    }

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    protected function defineFields(): FieldCollection
    {
        $fieldCollection = parent::defineFields();

        $prefixField = new StringField('element_prefix', 'elementPrefix');
        $prefixField->addFlags(new ApiAware());

        $fieldCollection->add($prefixField);

        $suffixField = new StringField('element_suffix', 'elementSuffix');
        $suffixField->addFlags(new ApiAware());

        $fieldCollection->add($suffixField);

        return $fieldCollection;
    }

    protected function getParentDefinitionClass(): string
    {
        return RangeIntervalListingFilterConfigurationDefinition::class;
    }
}
