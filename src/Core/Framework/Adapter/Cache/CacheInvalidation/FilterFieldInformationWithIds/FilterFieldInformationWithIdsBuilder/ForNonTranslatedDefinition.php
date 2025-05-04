<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Core\Framework\Adapter\Cache\CacheInvalidation\FilterFieldInformationWithIds\FilterFieldInformationWithIdsBuilder;

use ITB\ITBConfigurableListingFilters\Core\Framework\Adapter\Cache\CacheInvalidation\FilterFieldInformationWithIds\FilterFieldInformationWithIds;
use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Field;

final class ForNonTranslatedDefinition implements ForNonTranslatedDefinitionInterface
{
    public function build(
        EntityDefinition $targetEntityDefinition,
        string $pathToTargetEntityDefinition,
        array $ids
    ): FilterFieldInformationWithIds {
        $idField = $targetEntityDefinition->getPrimaryKeys()
            ->first();
        if (! $idField instanceof Field) {
            throw new \RuntimeException(sprintf(
                'Primary key field not found in definition "%s"',
                $targetEntityDefinition->getEntityName()
            ));
        }

        $ids = array_column($ids, $idField->getPropertyName());

        return new FilterFieldInformationWithIds($pathToTargetEntityDefinition, $targetEntityDefinition, $ids);
    }
}
