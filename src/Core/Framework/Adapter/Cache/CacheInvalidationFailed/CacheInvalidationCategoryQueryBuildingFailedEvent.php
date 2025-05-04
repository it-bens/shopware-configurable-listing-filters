<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Core\Framework\Adapter\Cache\CacheInvalidationFailed;

use ITB\ITBConfigurableListingFilters\Core\Framework\Adapter\Cache\CacheInvalidation\FilterFieldInformationWithIds\FilterFieldInformationWithIdsCollection;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\Event\EntityWrittenContainerEvent;

final class CacheInvalidationCategoryQueryBuildingFailedEvent extends CacheInvalidationFailedEvent
{
    /**
     * @codeCoverageIgnore
     */
    public function __construct(
        private readonly FilterFieldInformationWithIdsCollection $filterFieldInformationWithIds,
        \Throwable $exception,
        EntityWrittenContainerEvent $entityWrittenContainerEvent,
        Context $context,
    ) {
        parent::__construct($exception, $entityWrittenContainerEvent, $context);
    }

    /**
     * @codeCoverageIgnore
     */
    public function getFilterFieldInformationWithIds(): FilterFieldInformationWithIdsCollection
    {
        return $this->filterFieldInformationWithIds;
    }
}
