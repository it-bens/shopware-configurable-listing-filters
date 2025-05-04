<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration;

use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Checkbox\CheckboxListingFilterConfigurationCollection;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\MultiSelect\MultiSelectListingFilterConfigurationCollection;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Range\RangeListingFilterConfigurationCollection;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\RangeInterval\RangeIntervalListingFilterConfigurationCollection;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\MultiFilter;

final class ListingFilterConfigurationRepository implements ListingFilterConfigurationRepositoryInterface
{
    /**
     * @param EntityRepository<CheckboxListingFilterConfigurationCollection> $checkboxListingFilterConfigurationRepository
     * @param EntityRepository<MultiSelectListingFilterConfigurationCollection> $multiSelectListingFilterConfigurationRepository
     * @param EntityRepository<RangeListingFilterConfigurationCollection> $rangeListingFilterConfigurationRepository
     * @param EntityRepository<RangeIntervalListingFilterConfigurationCollection> $rangeIntervalListingFilterConfigurationRepository
     */
    public function __construct(
        private readonly EntityRepository $checkboxListingFilterConfigurationRepository,
        private readonly EntityRepository $multiSelectListingFilterConfigurationRepository,
        private readonly EntityRepository $rangeListingFilterConfigurationRepository,
        private readonly EntityRepository $rangeIntervalListingFilterConfigurationRepository
    ) {
    }

    public function getCheckboxListingFilterConfigurations(
        Context $context,
        ?string $salesChannelId,
        bool $loadSalesChannel = false
    ): CheckboxListingFilterConfigurationCollection {
        return $this->checkboxListingFilterConfigurationRepository->search(
            $this->buildCriteria($salesChannelId, $loadSalesChannel),
            $context
        )
            ->getEntities();
    }

    public function getMultiSelectListingFilterConfigurations(
        Context $context,
        ?string $salesChannelId,
        bool $loadSalesChannel = false
    ): MultiSelectListingFilterConfigurationCollection {
        return $this->multiSelectListingFilterConfigurationRepository->search(
            $this->buildCriteria($salesChannelId, $loadSalesChannel),
            $context
        )
            ->getEntities();
    }

    public function getRangeIntervalListingFilterConfigurations(
        Context $context,
        ?string $salesChannelId,
        bool $loadSalesChannel = false
    ): RangeIntervalListingFilterConfigurationCollection {
        $criteria = $this->buildCriteria($salesChannelId, $loadSalesChannel);

        $criteria->addAssociation('intervals');
        $criteria->addAssociation('intervals.rangeIntervalListingFilterConfiguration');

        $intervalsAssociation = $criteria->getAssociation('intervals');
        $intervalsAssociation->addAssociation('rangeIntervalListingFilterConfiguration');

        return $this->rangeIntervalListingFilterConfigurationRepository->search($criteria, $context)
            ->getEntities();
    }

    public function getRangeListingFilterConfigurations(
        Context $context,
        ?string $salesChannelId,
        bool $loadSalesChannel = false
    ): RangeListingFilterConfigurationCollection {
        return $this->rangeListingFilterConfigurationRepository->search(
            $this->buildCriteria($salesChannelId, $loadSalesChannel),
            $context
        )
            ->getEntities();
    }

    private function buildCriteria(?string $salesChannelId, bool $loadSalesChannel): Criteria
    {
        $criteria = new Criteria();

        if (is_string($salesChannelId)) {
            $criteria->addFilter(
                new MultiFilter(MultiFilter::CONNECTION_OR, [
                    new EqualsFilter('salesChannelId', null),
                    new EqualsFilter('salesChannelId', $salesChannelId),
                ])
            );
        }

        if ($loadSalesChannel) {
            $criteria->addAssociation('salesChannel');
        }

        return $criteria;
    }
}
