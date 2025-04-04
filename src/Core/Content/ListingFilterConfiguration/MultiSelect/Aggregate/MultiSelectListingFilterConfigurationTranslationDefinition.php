<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\MultiSelect\Aggregate;

use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Aggregate\ListingFilterConfigurationTranslation\ListingFilterConfigurationTranslationDefinition;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\MultiSelect\MultiSelectListingFilterConfigurationDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\ApiAware;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ListField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;

final class MultiSelectListingFilterConfigurationTranslationDefinition extends ListingFilterConfigurationTranslationDefinition
{
    public const ENTITY_NAME = 'itb_listing_filter_configuration_multi_select_translation';

    public function getCollectionClass(): string
    {
        return MultiSelectListingFilterConfigurationTranslationCollection::class;
    }

    /**
     * @return class-string<Entity>
     */
    public function getEntityClass(): string
    {
        return MultiSelectListingFilterConfigurationTranslationEntity::class;
    }

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    protected function defineFields(): FieldCollection
    {
        $fieldCollection = parent::defineFields();

        $allowedElementsField = new ListField('allowed_elements', 'allowedElements', StringField::class);
        $allowedElementsField->addFlags(new ApiAware());

        $fieldCollection->add($allowedElementsField);

        $prefixField = new StringField('element_prefix', 'elementPrefix');
        $prefixField->addFlags(new ApiAware());

        $fieldCollection->add($prefixField);

        $suffixField = new StringField('element_suffix', 'elementSuffix');
        $suffixField->addFlags(new ApiAware());

        $fieldCollection->add($suffixField);

        $explicitElementSortingField = new ListField('explicit_element_sorting', 'explicitElementSorting', StringField::class);
        $explicitElementSortingField->addFlags(new ApiAware());

        $fieldCollection->add($explicitElementSortingField);

        $forbiddenElementsField = new ListField('forbidden_elements', 'forbiddenElements', StringField::class);
        $forbiddenElementsField->addFlags(new ApiAware());

        $fieldCollection->add($forbiddenElementsField);

        return $fieldCollection;
    }

    protected function getParentDefinitionClass(): string
    {
        return MultiSelectListingFilterConfigurationDefinition::class;
    }
}
