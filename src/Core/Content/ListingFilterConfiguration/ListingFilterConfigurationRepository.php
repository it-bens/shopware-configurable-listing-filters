<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration;

use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Checkbox\CheckboxListingFilterConfigurationCollection;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\MultiSelect\MultiSelectListingFilterConfigurationCollection;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Range\RangeListingFilterConfigurationCollection;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\MultiFilter;
use Shopware\Core\System\SalesChannel\SalesChannelContext;

final class ListingFilterConfigurationRepository implements ListingFilterConfigurationRepositoryInterface
{
    /**
     * @param EntityRepository<CheckboxListingFilterConfigurationCollection> $checkboxListingFilterConfigurationRepository
     * @param EntityRepository<MultiSelectListingFilterConfigurationCollection> $multiSelectListingFilterConfigurationRepository
     * @param EntityRepository<RangeListingFilterConfigurationCollection> $rangeListingFilterConfigurationRepository
     */
    public function __construct(
        private readonly EntityRepository $checkboxListingFilterConfigurationRepository,
        private readonly EntityRepository $multiSelectListingFilterConfigurationRepository,
        private readonly EntityRepository $rangeListingFilterConfigurationRepository,
    ) {
    }

    public function getCheckboxListingFilterConfigurations(
        SalesChannelContext $context,
        bool $loadSalesChannel = false
    ): CheckboxListingFilterConfigurationCollection {
        return $this->checkboxListingFilterConfigurationRepository->search(
            $this->buildCriteria($context->getSalesChannelId(), $loadSalesChannel),
            $context->getContext()
        )
            ->getEntities();
    }

    public function getMultiSelectListingFilterConfigurations(
        SalesChannelContext $context,
        bool $loadSalesChannel = false
    ): MultiSelectListingFilterConfigurationCollection {
        return $this->multiSelectListingFilterConfigurationRepository->search(
            $this->buildCriteria($context->getSalesChannelId(), $loadSalesChannel),
            $context->getContext()
        )
            ->getEntities();
    }

    public function getRangeListingFilterConfigurations(
        SalesChannelContext $context,
        bool $loadSalesChannel = false
    ): RangeListingFilterConfigurationCollection {
        return $this->rangeListingFilterConfigurationRepository->search(
            $this->buildCriteria($context->getSalesChannelId(), $loadSalesChannel),
            $context->getContext()
        )
            ->getEntities();
    }

    private function buildCriteria(string $salesChannelId, bool $loadSalesChannel): Criteria
    {
        $criteria = new Criteria();
        $criteria->addFilter(
            new MultiFilter(MultiFilter::CONNECTION_OR, [
                new EqualsFilter('salesChannelId', null),
                new EqualsFilter('salesChannelId', $salesChannelId),
            ])
        );

        if ($loadSalesChannel) {
            $criteria->addAssociation('salesChannel');
        }

        return $criteria;
    }
}
