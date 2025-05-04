<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Core\Framework\Adapter\Cache\CacheInvalidation;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\ParameterType;
use Shopware\Core\Defaults;
use Shopware\Core\Framework\DataAbstractionLayer\Dbal\QueryBuilder;
use Shopware\Core\Framework\Uuid\Uuid;

/**
 * @codeCoverageIgnore
 */
final class QueryStarter implements QueryStarterInterface
{
    public function __construct(
        private readonly Connection $connection,
    ) {
    }

    public function startQuery(): QueryBuilder
    {
        $query = new QueryBuilder($this->connection);
        $query->select('LOWER(HEX(`product_category_tree`.`category_id`))')
            ->distinct();
        $query->from('product_category_tree');
        $query->innerJoin(
            'product_category_tree',
            'product',
            '`product`',
            'product.id = product_category_tree.product_id AND product_category_tree.product_version_id = product.version_id'
        );
        $query->andWhere('product.version_id = :versionId');
        $query->setParameter('versionId', Uuid::fromHexToBytes(Defaults::LIVE_VERSION), ParameterType::BINARY);

        return $query;
    }
}
