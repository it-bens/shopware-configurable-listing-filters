<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Range\Aggregate;

use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Aggregate\ListingFilterConfigurationTranslation\ListingFilterConfigurationTranslationDefinition;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Range\RangeListingFilterConfigurationDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\ApiAware;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;

class RangeListingFilterConfigurationTranslationDefinition extends ListingFilterConfigurationTranslationDefinition
{
    public const ENTITY_NAME = 'itb_lfc_range_translation';

    public function getCollectionClass(): string
    {
        return RangeListingFilterConfigurationTranslationCollection::class;
    }

    /**
     * @return class-string<Entity>
     */
    public function getEntityClass(): string
    {
        return RangeListingFilterConfigurationTranslationEntity::class;
    }

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    protected function defineFields(): FieldCollection
    {
        $fieldCollection = parent::defineFields();

        $unitField = new StringField('unit', 'unit');
        $unitField->addFlags(new ApiAware());

        $fieldCollection->add($unitField);

        return $fieldCollection;
    }

    protected function getParentDefinitionClass(): string
    {
        return RangeListingFilterConfigurationDefinition::class;
    }
}
