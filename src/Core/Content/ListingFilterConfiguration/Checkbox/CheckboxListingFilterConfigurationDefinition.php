<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Checkbox;

use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Checkbox\Aggregate\CheckboxListingFilterConfigurationTranslationDefinition;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\ListingFilterConfigurationDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\ApiAware;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\TranslationsAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;

final class CheckboxListingFilterConfigurationDefinition extends ListingFilterConfigurationDefinition
{
    public const ENTITY_NAME = 'itb_listing_filter_configuration_checkbox';

    public function getCollectionClass(): string
    {
        return CheckboxListingFilterConfigurationCollection::class;
    }

    /**
     * @return class-string<Entity>
     */
    public function getEntityClass(): string
    {
        return CheckboxListingFilterConfigurationEntity::class;
    }

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    protected function defineFields(): FieldCollection
    {
        $fieldCollection = parent::defineFields();

        $translationsAssociationField = new TranslationsAssociationField(
            CheckboxListingFilterConfigurationTranslationDefinition::class,
            self::ENTITY_NAME . '_id'
        );
        $translationsAssociationField->addFlags(new ApiAware(), new Required());

        $fieldCollection->add($translationsAssociationField);

        return $fieldCollection;
    }
}
