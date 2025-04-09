<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\MultiSelect;

use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\ListingFilterConfigurationDefinition;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\MultiSelect\Aggregate\MultiSelectListingFilterConfigurationTranslationDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\ApiAware;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\TranslatedField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\TranslationsAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;

class MultiSelectListingFilterConfigurationDefinition extends ListingFilterConfigurationDefinition
{
    public const ENTITY_NAME = 'itb_listing_filter_configuration_multi_select';

    public function getCollectionClass(): string
    {
        return MultiSelectListingFilterConfigurationCollection::class;
    }

    /**
     * @return class-string<Entity>
     */
    public function getEntityClass(): string
    {
        return MultiSelectListingFilterConfigurationEntity::class;
    }

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    protected function defineFields(): FieldCollection
    {
        $fieldCollection = parent::defineFields();

        $allowedElementsField = new TranslatedField('allowedElements');
        $allowedElementsField->addFlags(new ApiAware());

        $fieldCollection->add($allowedElementsField);

        $prefixField = new TranslatedField('elementPrefix');
        $prefixField->addFlags(new ApiAware());

        $fieldCollection->add($prefixField);

        $suffixField = new TranslatedField('elementSuffix');
        $suffixField->addFlags(new ApiAware());

        $fieldCollection->add($suffixField);

        $explicitElementSortingField = new TranslatedField('explicitElementSorting');
        $explicitElementSortingField->addFlags(new ApiAware());

        $fieldCollection->add($explicitElementSortingField);

        $forbiddenElementsField = new TranslatedField('forbiddenElements');
        $forbiddenElementsField->addFlags(new ApiAware());

        $fieldCollection->add($forbiddenElementsField);

        $sortingOrderField = new StringField('sorting_order', 'sortingOrder');
        $sortingOrderField->addFlags(new ApiAware(), new Required());

        $fieldCollection->add($sortingOrderField);

        $translationsAssociationField = new TranslationsAssociationField(
            MultiSelectListingFilterConfigurationTranslationDefinition::class,
            self::ENTITY_NAME . '_id'
        );
        $translationsAssociationField->addFlags(new ApiAware(), new Required());

        $fieldCollection->add($translationsAssociationField);

        return $fieldCollection;
    }
}
