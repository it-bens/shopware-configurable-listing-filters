<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Core\Framework\Adapter\Cache\CacheInvalidationFailed;

use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\Event\EntityWrittenContainerEvent;
use Shopware\Core\Framework\Event\ShopwareEvent;
use Symfony\Contracts\EventDispatcher\Event;

abstract class CacheInvalidationFailedEvent extends Event implements ShopwareEvent
{
    /**
     * @codeCoverageIgnore
     */
    public function __construct(
        private readonly \Throwable $exception,
        private readonly EntityWrittenContainerEvent $entityWrittenContainerEvent,
        private readonly Context $context,
    ) {
    }

    /**
     * @codeCoverageIgnore
     */
    public function getContext(): Context
    {
        return $this->context;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getEvent(): ?EntityWrittenContainerEvent
    {
        return $this->entityWrittenContainerEvent;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getException(): \Throwable
    {
        return $this->exception;
    }
}
