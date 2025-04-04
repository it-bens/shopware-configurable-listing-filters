<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Range;

use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\ListingFilterConfigurationDefinition;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Range\Aggregate\RangeListingFilterConfigurationTranslationDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\ApiAware;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\TranslatedField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\TranslationsAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;

final class RangeListingFilterConfigurationDefinition extends ListingFilterConfigurationDefinition
{
    public const ENTITY_NAME = 'itb_listing_filter_configuration_range';

    public function getCollectionClass(): string
    {
        return RangeListingFilterConfigurationCollection::class;
    }

    /**
     * @return class-string<Entity>
     */
    public function getEntityClass(): string
    {
        return RangeListingFilterConfigurationEntity::class;
    }

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    protected function defineFields(): FieldCollection
    {
        $fieldCollection = parent::defineFields();

        $unitField = new TranslatedField('unit');
        $unitField->addFlags(new ApiAware());

        $fieldCollection->add($unitField);

        $translationsAssociationField = new TranslationsAssociationField(
            RangeListingFilterConfigurationTranslationDefinition::class,
            self::ENTITY_NAME . '_id'
        );
        $translationsAssociationField->addFlags(new ApiAware(), new Required());

        $fieldCollection->add($translationsAssociationField);

        return $fieldCollection;
    }
}
