<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\ListingFilter\RangeInterval\Storefront;

use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\RangeInterval\Aggregate\Interval\RangeIntervalListingFilterConfigurationIntervalEntity;
use ITB\ITBConfigurableListingFilters\ListingFilter\MultiSelect\Storefront\Element;

final class ElementBuilder implements ElementBuilderInterface
{
    public function buildElement(RangeIntervalListingFilterConfigurationIntervalEntity $rangeIntervalEntity): Element
    {
        if ($rangeIntervalEntity->getTitle() !== null) {
            return new Element($rangeIntervalEntity->getId(), $rangeIntervalEntity->getTitle());
        }

        if ($rangeIntervalEntity->getMin() === null && $rangeIntervalEntity->getMax() === null) {
            throw new \RuntimeException(
                'The title is null and both min and max are null. This should be prevented by the pre-persistence validation.'
            );
        }

        $prefix = (string) $rangeIntervalEntity->getRangeIntervalListingFilterConfiguration()
            ->getElementPrefix();
        $suffix = (string) $rangeIntervalEntity->getRangeIntervalListingFilterConfiguration()
            ->getElementSuffix();

        if ($rangeIntervalEntity->getMin() === null) {
            $text = '< ' . $prefix . $rangeIntervalEntity->getMax() . $suffix;
            return new Element($rangeIntervalEntity->getId(), $text);
        }

        if ($rangeIntervalEntity->getMax() === null) {
            $text = '> ' . $prefix . $rangeIntervalEntity->getMin() . $suffix;
            return new Element($rangeIntervalEntity->getId(), $text);
        }

        $text = $prefix . $rangeIntervalEntity->getMin() . $suffix . ' - ' . $prefix . $rangeIntervalEntity->getMax() . $suffix;
        return new Element($rangeIntervalEntity->getId(), $text);
    }
}
