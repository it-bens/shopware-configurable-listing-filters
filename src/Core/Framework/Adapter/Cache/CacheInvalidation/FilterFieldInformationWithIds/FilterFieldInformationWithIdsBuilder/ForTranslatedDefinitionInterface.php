<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Core\Framework\Adapter\Cache\CacheInvalidation\FilterFieldInformationWithIds\FilterFieldInformationWithIdsBuilder;

use ITB\ITBConfigurableListingFilters\Core\Framework\Adapter\Cache\CacheInvalidation\FilterFieldInformationWithIds\FilterFieldInformationWithIds;
use ITB\ITBConfigurableListingFilters\Core\Framework\Adapter\Cache\CacheInvalidation\FilterFieldInformationWithIds\FilterFieldInformationWithIdsCollector;
use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;

/**
 * @phpstan-import-type IdsFromGetPrimaryKeys from FilterFieldInformationWithIdsCollector
 */
interface ForTranslatedDefinitionInterface
{
    /**
     * The array is a list of associated arrays, with the shape array<[id_field_name => id_field_value]>.
     *
     * @param IdsFromGetPrimaryKeys $ids
     */
    public function build(EntityDefinition $targetEntityDefinition, string $pathToTargetEntity, array $ids): FilterFieldInformationWithIds;
}
