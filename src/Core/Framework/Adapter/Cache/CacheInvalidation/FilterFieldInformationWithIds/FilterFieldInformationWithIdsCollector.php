<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Core\Framework\Adapter\Cache\CacheInvalidation\FilterFieldInformationWithIds;

use ITB\ITBConfigurableListingFilters\Core\Framework\Adapter\Cache\CacheInvalidation\FilterFieldInformation\FilterFieldInformationCollection;
use ITB\ITBConfigurableListingFilters\Core\Framework\Adapter\Cache\CacheInvalidation\FilterFieldInformationWithIds\FilterFieldInformationWithIdsBuilder\ForNonTranslatedDefinitionInterface as BuilderForNonTranslatedDefinitionInterface;
use ITB\ITBConfigurableListingFilters\Core\Framework\Adapter\Cache\CacheInvalidation\FilterFieldInformationWithIds\FilterFieldInformationWithIdsBuilder\ForTranslatedDefinitionInterface as BuilderForTranslatedDefinitionInterface;
use Shopware\Core\Framework\DataAbstractionLayer\EntityTranslationDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Event\EntityWrittenContainerEvent;

/**
 * @phpstan-type IdsFromGetPrimaryKeys array<int, array<string, string>>
 */
final class FilterFieldInformationWithIdsCollector implements FilterFieldInformationWithIdsCollectorInterface
{
    public function __construct(
        private readonly BuilderForTranslatedDefinitionInterface $builderForTranslatedDefinition,
        private readonly BuilderForNonTranslatedDefinitionInterface $builderForNonTranslatedDefinition,
    ) {
    }

    public function collect(
        EntityWrittenContainerEvent $event,
        FilterFieldInformationCollection $filterFieldInformationCollection
    ): FilterFieldInformationWithIdsCollection {
        $filterFieldInformationWithIdsCollection = new FilterFieldInformationWithIdsCollection();
        foreach ($filterFieldInformationCollection->getFilterFieldTargetInformations() as $filterFieldTargetInformation) {
            $pathToTargetEntity = $filterFieldTargetInformation->pathToTargetEntity;
            $targetEntityDefinition = $filterFieldTargetInformation->targetEntityDefinition;

            $propertyNames = $filterFieldInformationCollection->getFieldPropertyNamesForDefinition(
                $targetEntityDefinition->getEntityName()
            );

            /**
             * The array is a list of associated arrays, with the shape array<[id_field_name => id_field_value]>.
             *
             * @var IdsFromGetPrimaryKeys $ids
             */
            $ids = $event->getPrimaryKeysWithPropertyChange($targetEntityDefinition->getEntityName(), $propertyNames);
            if ($ids === []) {
                continue;
            }

            if ($targetEntityDefinition instanceof EntityTranslationDefinition) {
                $filterFieldInformationWithIds = $this->builderForTranslatedDefinition->build(
                    $targetEntityDefinition,
                    $pathToTargetEntity,
                    $ids
                );
                $filterFieldInformationWithIdsCollection->add($filterFieldInformationWithIds);

                continue;
            }

            $filterFieldInformationWithIds = $this->builderForNonTranslatedDefinition->build(
                $targetEntityDefinition,
                $pathToTargetEntity,
                $ids
            );
            $filterFieldInformationWithIdsCollection->add($filterFieldInformationWithIds);
        }

        return $filterFieldInformationWithIdsCollection;
    }
}
