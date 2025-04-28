<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration;

use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Checkbox\CheckboxListingFilterConfigurationCollection;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\MultiSelect\MultiSelectListingFilterConfigurationCollection;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Range\RangeListingFilterConfigurationCollection;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\RangeInterval\RangeIntervalListingFilterConfigurationCollection;
use Traversable;

/**
 * @implements \IteratorAggregate<ListingFilterConfigurationEntity>
 */
final class ListingFilterConfigurationCollection implements \IteratorAggregate
{
    public function __construct(
        private readonly CheckboxListingFilterConfigurationCollection $checkboxListingFilterConfigurationCollection,
        private readonly MultiSelectListingFilterConfigurationCollection $multiSelectListingFilterConfigurationCollection,
        private readonly RangeListingFilterConfigurationCollection $rangeListingFilterConfigurationCollection,
        private readonly RangeIntervalListingFilterConfigurationCollection $rangeIntervalListingFilterConfigurationCollection,
    ) {
    }

    public function getIterator(): Traversable
    {
        return new \ArrayIterator($this->getListingFilterConfigurations());
    }

    /**
     * @return array<ListingFilterConfigurationEntity>
     */
    public function getListingFilterConfigurations(): array
    {
        return $this->sortFilterConfigurations(
            $this->checkboxListingFilterConfigurationCollection,
            $this->multiSelectListingFilterConfigurationCollection,
            $this->rangeListingFilterConfigurationCollection,
            $this->rangeIntervalListingFilterConfigurationCollection
        );
    }

    /**
     * @return array<ListingFilterConfigurationEntity>
     */
    private function sortFilterConfigurations(
        CheckboxListingFilterConfigurationCollection $checkboxListingFilterConfigurationCollection,
        MultiSelectListingFilterConfigurationCollection $multiSelectListingFilterConfigurationCollection,
        RangeListingFilterConfigurationCollection $rangeListingFilterConfigurationCollection,
        RangeIntervalListingFilterConfigurationCollection $rangeIntervalListingFilterConfigurationCollection
    ): array {
        $allConfigurations = array_merge(
            $checkboxListingFilterConfigurationCollection->getElements(),
            $multiSelectListingFilterConfigurationCollection->getElements(),
            $rangeListingFilterConfigurationCollection->getElements(),
            $rangeIntervalListingFilterConfigurationCollection->getElements()
        );

        usort(
            $allConfigurations,
            function (ListingFilterConfigurationEntity $a, ListingFilterConfigurationEntity $b): int {
                $positionA = $a->getPosition();
                $positionB = $b->getPosition();

                if ($positionA === $positionB) {
                    return strcmp($a->getFullyQualifiedDalField(), $b->getFullyQualifiedDalField());
                }

                if ($positionA === null) {
                    return $positionB === null ? strcmp($a->getFullyQualifiedDalField(), $b->getFullyQualifiedDalField()) : 1;
                }

                if ($positionB === null) {
                    return -1;
                }

                return $positionA <=> $positionB;
            }
        );

        return $allConfigurations;
    }
}
