<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Core\Framework\Adapter\Cache\CacheInvalidation\FilterFieldInformationWithIds;

use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;

final class FilterFieldInformationWithIds
{
    /**
     * @codeCoverageIgnore
     *
     * @param array<int, string> $ids
     */
    public function __construct(
        public readonly string $pathToTargetEntityDefinition,
        public readonly EntityDefinition $targetEntityDefinition,
        public readonly array $ids,
    ) {
    }

    public function fullyQualifiedTargetDefinitionIdField(): string
    {
        return $this->pathToTargetEntityDefinition . '.id';
    }
}
