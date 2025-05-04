<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Core\Framework\Adapter\Cache\CacheInvalidationFailed;

use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\Dbal\QueryBuilder;
use Shopware\Core\Framework\DataAbstractionLayer\Event\EntityWrittenContainerEvent;

final class CacheInvalidationCategoryQueryExecutionFailedEvent extends CacheInvalidationFailedEvent
{
    /**
     * @codeCoverageIgnore
     */
    public function __construct(
        private readonly QueryBuilder $queryBuilder,
        \Throwable $exception,
        EntityWrittenContainerEvent $entityWrittenContainerEvent,
        Context $context,
    ) {
        parent::__construct($exception, $entityWrittenContainerEvent, $context);
    }

    /**
     * @codeCoverageIgnore
     */
    public function getQueryBuilder(): QueryBuilder
    {
        return $this->queryBuilder;
    }
}
