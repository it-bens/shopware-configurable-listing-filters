<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\RangeInterval;

use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\ListingFilterConfigurationDefinition;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\RangeInterval\Aggregate\RangeIntervalListingFilterConfigurationIntervalDefinition;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\RangeInterval\Aggregate\RangeIntervalListingFilterConfigurationTranslationDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\ApiAware;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\OneToManyAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\TranslatedField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\TranslationsAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;

class RangeIntervalListingFilterConfigurationDefinition extends ListingFilterConfigurationDefinition
{
    public const ENTITY_NAME = 'itb_lfc_range_interval';

    public function getCollectionClass(): string
    {
        return RangeIntervalListingFilterConfigurationCollection::class;
    }

    /**
     * @return class-string<Entity>
     */
    public function getEntityClass(): string
    {
        return RangeIntervalListingFilterConfigurationEntity::class;
    }

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    protected function defineFields(): FieldCollection
    {
        $fieldCollection = parent::defineFields();

        $prefixField = new TranslatedField('elementPrefix');
        $prefixField->addFlags(new ApiAware());

        $fieldCollection->add($prefixField);

        $suffixField = new TranslatedField('elementSuffix');
        $suffixField->addFlags(new ApiAware());

        $fieldCollection->add($suffixField);

        $intervalsAssociationField = new OneToManyAssociationField(
            'intervals',
            RangeIntervalListingFilterConfigurationIntervalDefinition::class,
            self::ENTITY_NAME . '_id'
        );
        $intervalsAssociationField->addFlags(new ApiAware(), new Required());

        $fieldCollection->add($intervalsAssociationField);

        $translationsAssociationField = new TranslationsAssociationField(
            RangeIntervalListingFilterConfigurationTranslationDefinition::class,
            self::ENTITY_NAME . '_id'
        );
        $translationsAssociationField->addFlags(new ApiAware(), new Required());

        $fieldCollection->add($translationsAssociationField);

        return $fieldCollection;
    }
}
