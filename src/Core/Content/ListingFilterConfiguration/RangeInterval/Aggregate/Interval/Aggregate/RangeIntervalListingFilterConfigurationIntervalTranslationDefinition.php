<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\RangeInterval\Aggregate\Interval\Aggregate;

use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\RangeInterval\Aggregate\Interval\RangeIntervalListingFilterConfigurationIntervalDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityTranslationDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\ApiAware;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;

class RangeIntervalListingFilterConfigurationIntervalTranslationDefinition extends EntityTranslationDefinition
{
    public const ENTITY_NAME = 'itb_lfc_range_interval_interval_translation';

    public function getCollectionClass(): string
    {
        return RangeIntervalListingFilterConfigurationIntervalTranslationCollection::class;
    }

    /**
     * @return class-string<Entity>
     */
    public function getEntityClass(): string
    {
        return RangeIntervalListingFilterConfigurationIntervalTranslationEntity::class;
    }

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([(new StringField('title', 'title'))->addFlags(new ApiAware())]);
    }

    protected function getParentDefinitionClass(): string
    {
        return RangeIntervalListingFilterConfigurationIntervalDefinition::class;
    }
}
