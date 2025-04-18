<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Core\Content\Product\SalesChannel\Listing\Service;

use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Checkbox\CheckboxListingFilterConfigurationEntity;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\ListingFilterConfigurationCollection;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\MultiSelect\MultiSelectListingFilterConfigurationEntity;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Range\RangeListingFilterConfigurationEntity;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\RangeInterval\RangeIntervalListingFilterConfigurationEntity;
use ITB\ITBConfigurableListingFilters\ListingFilter\Checkbox\Dal\FilterBuilderInterface as CheckboxFilterBuilder;
use ITB\ITBConfigurableListingFilters\ListingFilter\Checkbox\Dal\RequestValueBuilderInterface as CheckboxRequestValueBuilder;
use ITB\ITBConfigurableListingFilters\ListingFilter\MultiSelect\Dal\FilterBuilderInterface as MultiSelectFilterBuilder;
use ITB\ITBConfigurableListingFilters\ListingFilter\MultiSelect\Dal\RequestValueBuilderInterface as MultiSelectRequestValueBuilder;
use ITB\ITBConfigurableListingFilters\ListingFilter\Range\Dal\FilterBuilderInterface as RangeFilterBuilder;
use ITB\ITBConfigurableListingFilters\ListingFilter\Range\Dal\RequestValueBuilderInterface as RangeRequestValueBuilder;
use ITB\ITBConfigurableListingFilters\ListingFilter\RangeInterval\Dal\FilterBuilderInterface as RangeIntervalFilterBuilder;
use ITB\ITBConfigurableListingFilters\ListingFilter\RangeInterval\Dal\RequestValueBuilderInterface as RangeIntervalRequestValueBuilder;
use Shopware\Core\Content\Product\SalesChannel\Listing\FilterCollection;
use Symfony\Component\HttpFoundation\Request;

final class FilterCollectionEnricher implements FilterCollectionEnricherInterface
{
    public function __construct(
        private readonly CheckboxRequestValueBuilder $checkboxRequestValueBuilder,
        private readonly CheckboxFilterBuilder $checkboxFilterBuilder,
        private readonly MultiSelectRequestValueBuilder $multiSelectRequestValueBuilder,
        private readonly MultiSelectFilterBuilder $multiSelectFilterBuilder,
        private readonly RangeRequestValueBuilder $rangeRequestValueBuilder,
        private readonly RangeFilterBuilder $rangeFilterBuilder,
        private readonly RangeIntervalRequestValueBuilder $rangeIntervalRequestValueBuilder,
        private readonly RangeIntervalFilterBuilder $rangeIntervalFilterBuilder,
    ) {
    }

    public function enrichFilterCollection(
        ListingFilterConfigurationCollection $listingFilterConfigurationCollection,
        Request $request,
        FilterCollection $filterCollection,
    ): void {
        foreach ($listingFilterConfigurationCollection as $listingFilterConfigurationEntity) {
            if ($listingFilterConfigurationEntity->getEnabled() === false) {
                continue;
            }

            switch ($listingFilterConfigurationEntity::class) {
                case CheckboxListingFilterConfigurationEntity::class:
                    $requestValue = $this->checkboxRequestValueBuilder->buildRequestValue($listingFilterConfigurationEntity, $request);
                    $filterCollection->add($this->checkboxFilterBuilder->buildFilter($listingFilterConfigurationEntity, $requestValue));
                    break;
                case MultiSelectListingFilterConfigurationEntity::class:
                    $requestValue = $this->multiSelectRequestValueBuilder->buildRequestValue($listingFilterConfigurationEntity, $request);
                    $filterCollection->add($this->multiSelectFilterBuilder->buildFilter($listingFilterConfigurationEntity, $requestValue));
                    break;
                case RangeListingFilterConfigurationEntity::class:
                    $requestValue = $this->rangeRequestValueBuilder->buildRequestValue($listingFilterConfigurationEntity, $request);
                    $filterCollection->add($this->rangeFilterBuilder->buildFilter($listingFilterConfigurationEntity, $requestValue));
                    break;
                case RangeIntervalListingFilterConfigurationEntity::class:
                    $requestValue = $this->rangeIntervalRequestValueBuilder->buildRequestValue($listingFilterConfigurationEntity, $request);
                    $filterCollection->add(
                        $this->rangeIntervalFilterBuilder->buildFilter($listingFilterConfigurationEntity, $requestValue)
                    );
                    break;
            }
        }
    }
}
