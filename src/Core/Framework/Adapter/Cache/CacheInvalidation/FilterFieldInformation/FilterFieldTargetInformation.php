<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Core\Framework\Adapter\Cache\CacheInvalidation\FilterFieldInformation;

use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;

final class FilterFieldTargetInformation
{
    /**
     * @codeCoverageIgnore
     */
    public function __construct(
        public readonly string $pathToTargetEntity,
        public readonly EntityDefinition $targetEntityDefinition
    ) {
    }
}
