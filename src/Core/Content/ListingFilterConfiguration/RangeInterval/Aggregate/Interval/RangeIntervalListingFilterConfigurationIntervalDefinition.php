<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\RangeInterval\Aggregate\Interval;

use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\RangeInterval\RangeIntervalListingFilterConfigurationDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\FkField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\ApiAware;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IdField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IntField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToOneAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;

class RangeIntervalListingFilterConfigurationIntervalDefinition extends EntityDefinition
{
    public const ENTITY_NAME = 'itb_lfc_range_interval_interval';

    public function getCollectionClass(): string
    {
        return RangeIntervalListingFilterConfigurationIntervalCollection::class;
    }

    /**
     * @return class-string<Entity>
     */
    public function getEntityClass(): string
    {
        return RangeIntervalListingFilterConfigurationIntervalEntity::class;
    }

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new IdField('id', 'id'))->addFlags(new Required(), new ApiAware(), new PrimaryKey()),

            (new IntField('min', 'min'))->addFlags(new ApiAware()),
            (new IntField('max', 'max'))->addFlags(new ApiAware()),
            (new IntField('position', 'position'))->addFlags(new ApiAware()),

            (new FkField(
                RangeIntervalListingFilterConfigurationDefinition::ENTITY_NAME . '_id',
                'rangeIntervalListingFilterConfigurationId',
                RangeIntervalListingFilterConfigurationDefinition::class,
                'id'
            ))->addFlags(new Required()),
            new ManyToOneAssociationField(
                'rangeIntervalListingFilterConfiguration',
                RangeIntervalListingFilterConfigurationDefinition::ENTITY_NAME . '_id',
                RangeIntervalListingFilterConfigurationDefinition::class,
                'id'
            ),
        ]);
    }

    protected function getParentDefinitionClass(): string
    {
        return RangeIntervalListingFilterConfigurationDefinition::class;
    }
}
