<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Core\Framework\Adapter\Cache\CacheInvalidation\FilterFieldInformationWithIds\FilterFieldInformationWithIdsBuilder;

use ITB\ITBConfigurableListingFilters\Core\Framework\Adapter\Cache\CacheInvalidation\FilterFieldInformationWithIds\FilterFieldInformationWithIds;
use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\FkField;

final class ForTranslatedDefinition implements ForTranslatedDefinitionInterface
{
    public function build(EntityDefinition $targetEntityDefinition, string $pathToTargetEntity, array $ids): FilterFieldInformationWithIds
    {
        // Get the FK field that is not referencing the language
        $idField = null;
        foreach ($targetEntityDefinition->getPrimaryKeys() as $field) {
            if ($field instanceof FkField && $field->getReferenceDefinition()->getEntityName() !== 'language') {
                $idField = $field;
                break;
            }
        }

        if (! $idField instanceof FkField) {
            throw new \RuntimeException(sprintf(
                'FK field that does not reference the language not found in translation definition "%s"',
                $targetEntityDefinition->getEntityName()
            ));
        }

        $ids = array_column($ids, $idField->getPropertyName());
        $originalTargetEntityDefinition = $targetEntityDefinition;
        $targetEntityDefinition = $targetEntityDefinition->getParentDefinition();
        if (! $targetEntityDefinition instanceof EntityDefinition) {
            throw new \RuntimeException(sprintf(
                'Parent definition not found for FK field "%s" of translation definition "%s"',
                $idField->getPropertyName(),
                $originalTargetEntityDefinition->getEntityName()
            ));
        }

        return new FilterFieldInformationWithIds($pathToTargetEntity, $targetEntityDefinition, $ids);
    }
}
