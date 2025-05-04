<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Core\Framework\Adapter\Cache\CacheInvalidation;

use Doctrine\DBAL\ArrayParameterType;
use ITB\ITBConfigurableListingFilters\Core\Framework\Adapter\Cache\CacheInvalidation\FilterFieldInformationWithIds\FilterFieldInformationWithIdsCollection;
use Shopware\Core\Content\Product\ProductDefinition;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\Dbal\EntityDefinitionQueryHelper;
use Shopware\Core\Framework\DataAbstractionLayer\Dbal\QueryBuilder;
use Shopware\Core\Framework\Uuid\Uuid;

final class QueryExtender implements QueryExtenderInterface
{
    public function __construct(
        private readonly ProductDefinition $productDefinition,
        private readonly EntityDefinitionQueryHelper $queryHelper,
    ) {
    }

    public function extendQuery(
        QueryBuilder $query,
        FilterFieldInformationWithIdsCollection $filterFieldInformationWithIdsCollection,
        Context $context
    ): void {
        $orWhereParts = [];
        foreach ($filterFieldInformationWithIdsCollection->allWithoutFilterFieldInformationsWithoutIds() as $filterFieldInformationIds) {
            $idsParameterName = $filterFieldInformationIds->targetEntityDefinition->getEntityName() . '_id';
            $this->queryHelper->resolveAccessor(
                $filterFieldInformationIds->fullyQualifiedTargetDefinitionIdField(),
                $this->productDefinition,
                'product',
                $query,
                $context
            );

            $orWhereParts[] = '`' . $filterFieldInformationIds->pathToTargetEntityDefinition . '`.`id`' . ' IN (:' . $idsParameterName . ')';
            $query->setParameter($idsParameterName, Uuid::fromHexToBytesList($filterFieldInformationIds->ids), ArrayParameterType::BINARY);
        }

        $query->andWhere($query->expr()->or(...$orWhereParts));
    }
}
