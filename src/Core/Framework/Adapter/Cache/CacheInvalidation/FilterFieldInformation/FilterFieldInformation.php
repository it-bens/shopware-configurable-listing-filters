<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Core\Framework\Adapter\Cache\CacheInvalidation\FilterFieldInformation;

use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Field;

final class FilterFieldInformation
{
    /**
     * @codeCoverageIgnore
     */
    public function __construct(
        public readonly string $fullyQualifiedDalField,
        public readonly EntityDefinition $targetDefinition,
        public readonly Field $field,
    ) {
    }
}
