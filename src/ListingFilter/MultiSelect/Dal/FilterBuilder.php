<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\ListingFilter\MultiSelect\Dal;

use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\MultiSelect\MultiSelectListingFilterConfigurationEntity;
use Shopware\Core\Content\Product\SalesChannel\Listing\Filter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Aggregation\Bucket\FilterAggregation;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Aggregation\Bucket\TermsAggregation;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\AndFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsAnyFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\MultiFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\NotFilter;

final class FilterBuilder implements FilterBuilderInterface
{
    public function buildFilter(MultiSelectListingFilterConfigurationEntity $configurationEntity, RequestValue $requestValue): Filter
    {
        $termAggregation = new TermsAggregation(
            $configurationEntity->getAggregationName(),
            $configurationEntity->getFullyQualifiedDalField()
        );
        $aggregationFilter = [
            new NotFilter(MultiFilter::CONNECTION_AND, [new EqualsFilter($configurationEntity->getFullyQualifiedDalField(), null)]),
        ];
        if ($configurationEntity->getAllowedElements() !== []) {
            $aggregationFilter[] = new EqualsAnyFilter(
                $configurationEntity->getFullyQualifiedDalField(),
                $configurationEntity->getAllowedElements() ?? []
            );
        }

        if ($configurationEntity->getForbiddenElements() !== []) {
            $aggregationFilter[] = new NotFilter(MultiFilter::CONNECTION_AND, [
                new EqualsAnyFilter($configurationEntity->getFullyQualifiedDalField(), $configurationEntity->getForbiddenElements() ?? []),
            ]);
        }

        $filteredTermsAggregation = new FilterAggregation(
            $configurationEntity->getAggregationName() . '-filter',
            $termAggregation,
            $aggregationFilter
        );

        $filter = new AndFilter([
            new NotFilter(MultiFilter::CONNECTION_AND, [new EqualsFilter($configurationEntity->getFullyQualifiedDalField(), null)]),
            new EqualsAnyFilter($configurationEntity->getFullyQualifiedDalField(), $requestValue->values),
        ]);

        return new Filter(
            $configurationEntity->getFilterName(),
            $requestValue->isFiltered(),
            [$filteredTermsAggregation],
            $filter,
            $requestValue->values
        );
    }
}
