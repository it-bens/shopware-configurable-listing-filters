<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Aggregate\ListingFilterConfigurationTranslation;

use Shopware\Core\Framework\DataAbstractionLayer\EntityTranslationDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\ApiAware;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;

abstract class ListingFilterConfigurationTranslationDefinition extends EntityTranslationDefinition
{
    abstract public function getEntityName(): string;

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([(new StringField('display_name', 'displayName'))->addFlags(new ApiAware())]);
    }
}
