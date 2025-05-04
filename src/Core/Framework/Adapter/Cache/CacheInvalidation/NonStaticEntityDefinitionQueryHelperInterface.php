<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Core\Framework\Adapter\Cache\CacheInvalidation;

use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;

interface NonStaticEntityDefinitionQueryHelperInterface
{
    public function getAssociatedDefinition(EntityDefinition $rootDefinition, string $field): EntityDefinition;
}
