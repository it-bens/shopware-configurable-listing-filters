<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\RangeInterval\Aggregate;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

/**
 * @extends EntityCollection<RangeIntervalListingFilterConfigurationIntervalEntity>
 */
class RangeIntervalListingFilterConfigurationIntervalCollection extends EntityCollection
{
    /**
     * @api
     */
    public function getApiAlias(): string
    {
        return 'itb_lfc_collection_range_interval_interval';
    }

    /**
     * @return array<RangeIntervalListingFilterConfigurationIntervalEntity>
     */
    public function getElementsSortedByPosition(): array
    {
        $clone = $this->createNew($this->elements);
        $clone->sort(
            static function (
                RangeIntervalListingFilterConfigurationIntervalEntity $a,
                RangeIntervalListingFilterConfigurationIntervalEntity $b
            ): int {
                if ($a->getPosition() === $b->getPosition()) {
                    return 0;
                }

                return ($a->getPosition() < $b->getPosition()) ? -1 : 1;
            },
        );

        return array_values($clone->getElements());
    }

    public function getIterator(): \Traversable
    {
        yield from $this->getElementsSortedByPosition();
    }

    /**
     * @api
     */
    protected function getExpectedClass(): string
    {
        return RangeIntervalListingFilterConfigurationIntervalEntity::class;
    }
}
