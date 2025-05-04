<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Core\Framework\Adapter\Cache\CacheInvalidation\FilterFieldInformationWithIds;

final class FilterFieldInformationWithIdsCollection
{
    /**
     * @var array<FilterFieldInformationWithIds>
     */
    private array $filterFieldInformationWithIdsSets = [];

    public function add(FilterFieldInformationWithIds $entityDefinitionIds): void
    {
        $this->filterFieldInformationWithIdsSets[] = $entityDefinitionIds;
    }

    /**
     * @return array<FilterFieldInformationWithIds>
     */
    public function allWithoutFilterFieldInformationsWithoutIds(): array
    {
        return array_filter(
            $this->filterFieldInformationWithIdsSets,
            static fn (FilterFieldInformationWithIds $filterFieldInformationWithIds): bool => $filterFieldInformationWithIds->ids !== []
        );
    }
}
