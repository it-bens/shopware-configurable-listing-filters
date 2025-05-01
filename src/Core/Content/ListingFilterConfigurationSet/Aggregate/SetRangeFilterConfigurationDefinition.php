<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfigurationSet\Aggregate;

use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Range\RangeListingFilterConfigurationDefinition;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfigurationSet\ListingFilterConfigurationSetDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\FkField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\ApiAware;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToOneAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;
use Shopware\Core\Framework\DataAbstractionLayer\MappingEntityDefinition;

class SetRangeFilterConfigurationDefinition extends MappingEntityDefinition
{
    final public const ENTITY_NAME = 'itb_lfc_set_itb_lfc_range';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new FkField('itb_lfc_set_id', 'setId', ListingFilterConfigurationSetDefinition::class))->addFlags(
                new ApiAware(),
                new PrimaryKey(),
                new Required()
            ),
            (new FkField('itb_lfc_range_id', 'configurationId', RangeListingFilterConfigurationDefinition::class))->addFlags(
                new ApiAware(),
                new PrimaryKey(),
                new Required()
            ),
            new ManyToOneAssociationField('set', 'itb_lfc_set_id', ListingFilterConfigurationSetDefinition::class, 'id', false),
            (new ManyToOneAssociationField(
                'configuration',
                'itb_lfc_range_id',
                RangeListingFilterConfigurationDefinition::class,
                'id',
                false
            ))->addFlags(new ApiAware()),
        ]);
    }
}
