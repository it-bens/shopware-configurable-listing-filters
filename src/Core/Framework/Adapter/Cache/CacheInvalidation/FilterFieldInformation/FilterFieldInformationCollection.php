<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Core\Framework\Adapter\Cache\CacheInvalidation\FilterFieldInformation;

final class FilterFieldInformationCollection
{
    /**
     * @var array<FilterFieldInformation>
     */
    private array $filterFieldInformations = [];

    public function add(FilterFieldInformation $filterFieldInformation): void
    {
        $this->filterFieldInformations[] = $filterFieldInformation;
    }

    /**
     * @return array<string>
     */
    public function getFieldPropertyNamesForDefinition(string $entityName): array
    {
        $fieldPropertyNames = [];
        foreach ($this->filterFieldInformations as $filterFieldInformation) {
            if ($filterFieldInformation->targetDefinition->getEntityName() === $entityName) {
                $fieldPropertyNames[] = $filterFieldInformation->field->getPropertyName();
            }
        }

        return $fieldPropertyNames;
    }

    /**
     * @return array<FilterFieldTargetInformation>
     */
    public function getFilterFieldTargetInformations(): array
    {
        $filterFieldTargetInformations = [];
        foreach ($this->filterFieldInformations as $filterFieldInformation) {
            $pathToTargetEntityParts = explode('.', $filterFieldInformation->fullyQualifiedDalField);
            $pathToTargetEntity = array_slice($pathToTargetEntityParts, 0, -1);

            $filterFieldTargetInformations[] = new FilterFieldTargetInformation(implode(
                '.',
                $pathToTargetEntity
            ), $filterFieldInformation->targetDefinition);
        }

        return $filterFieldTargetInformations;
    }
}
