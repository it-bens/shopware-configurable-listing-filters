<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfigurationSet;

use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Checkbox\CheckboxListingFilterConfigurationDefinition;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfigurationSet\Aggregate\SetCheckboxFilterConfigurationDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\ApiAware;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\WriteProtected;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IdField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ListField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToManyAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToManyIdField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;

class ListingFilterConfigurationSetDefinition extends EntityDefinition
{
    public const ENTITY_NAME = 'itb_lfc_set';

    public function getCollectionClass(): string
    {
        return ListingFilterConfigurationSetCollection::class;
    }

    /**
     * @return class-string<Entity>
     */
    public function getEntityClass(): string
    {
        return ListingFilterConfigurationSetEntity::class;
    }

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new IdField('id', 'id'))->addFlags(new Required(), new ApiAware(), new PrimaryKey()),

            (new StringField('technical_name', 'technicalName'))->addFlags(new ApiAware(), new Required()),

            (new ManyToManyAssociationField(
                'checkboxConfigurations',
                CheckboxListingFilterConfigurationDefinition::class,
                SetCheckboxFilterConfigurationDefinition::class,
                'itb_lfc_set_id',
                'itb_lfc_checkbox_id'
            ))->addFlags(new ApiAware()),
            (new ManyToManyIdField('itb_lfc_checkbox_ids', 'checkboxConfigurationIds', 'checkboxConfigurations'))->addFlags(new ApiAware()),

            (new ManyToManyAssociationField(
                'multiSelectConfigurations',
                CheckboxListingFilterConfigurationDefinition::class,
                SetCheckboxFilterConfigurationDefinition::class,
                'itb_lfc_set_id',
                'itb_lfc_multi_select_id'
            ))->addFlags(new ApiAware()),
            (new ManyToManyIdField('itb_lfc_multi_select_ids', 'multiSelectConfigurationIds', 'multiSelectConfigurations'))->addFlags(
                new ApiAware()
            ),

            (new ManyToManyAssociationField(
                'rangeConfigurations',
                CheckboxListingFilterConfigurationDefinition::class,
                SetCheckboxFilterConfigurationDefinition::class,
                'itb_lfc_set_id',
                'itb_lfc_range_id'
            ))->addFlags(new ApiAware()),
            (new ManyToManyIdField('itb_lfc_range_ids', 'rangeConfigurationIds', 'rangeConfigurations'))->addFlags(new ApiAware()),

            (new ManyToManyAssociationField(
                'rangeIntervalConfigurations',
                CheckboxListingFilterConfigurationDefinition::class,
                SetCheckboxFilterConfigurationDefinition::class,
                'itb_lfc_set_id',
                'itb_lfc_range_interval_id'
            ))->addFlags(new ApiAware()),
            (new ManyToManyIdField('itb_lfc_range_interval_ids', 'rangeIntervalConfigurationIds', 'rangeIntervalConfigurations'))->addFlags(
                new ApiAware()
            ),

            (new ListField('category_tree', 'categoryTree', IdField::class))->addFlags(new ApiAware(), new WriteProtected()),
            (new ManyToManyIdField('category_ids', 'categoryIds', 'categories'))->addFlags(new ApiAware()),
        ]);
    }
}
