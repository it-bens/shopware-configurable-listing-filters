<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Test\PHPUnit\Unit\Core\Content\ListingFilterConfiguration;

use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Checkbox\CheckboxListingFilterConfigurationCollection;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Checkbox\CheckboxListingFilterConfigurationDefinition;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\ListingFilterConfigurationRepository;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\MultiSelect\MultiSelectListingFilterConfigurationCollection;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\MultiSelect\MultiSelectListingFilterConfigurationDefinition;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Range\RangeListingFilterConfigurationCollection;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Range\RangeListingFilterConfigurationDefinition;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\RangeInterval\RangeIntervalListingFilterConfigurationCollection;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\EntitySearchResult;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\MultiFilter;
use Shopware\Core\Framework\Uuid\Uuid;

#[CoversClass(ListingFilterConfigurationRepository::class)]
final class ListingFilterConfigurationRepositoryTest extends TestCase
{
    public static function getFilterConfigurationsProvider(): \Generator
    {
        yield 'with sales channel loading' => [Uuid::randomHex(), true];
        yield 'without sales channel loading' => [Uuid::randomHex(), false];
    }

    #[DataProvider('getFilterConfigurationsProvider')]
    public function testGetCheckboxListingFilterConfigurations(string $salesChannelId, bool $loadSalesChannel): void
    {
        $context = $this->createStub(Context::class);
        $checkboxListingFilterConfigurationCollection = $this->createMock(CheckboxListingFilterConfigurationCollection::class);

        $checkboxListingFilterConfigurationRepository = $this->createMock(EntityRepository::class);
        $checkboxListingFilterConfigurationRepository->method('search')
            ->willReturnCallback(function (Criteria $criteriaArgument, Context $contextArgument) use (
                $salesChannelId,
                $loadSalesChannel,
                $context,
                $checkboxListingFilterConfigurationCollection
            ): EntitySearchResult {
                $criteriaArgumentFilters = $criteriaArgument->getFilters();
                $this->assertCount(1, $criteriaArgumentFilters);

                $salesChannelIdOrNullFilter = array_values($criteriaArgumentFilters)[0];
                $this->assertInstanceOf(MultiFilter::class, $salesChannelIdOrNullFilter);
                /** @var MultiFilter $salesChannelIdOrNullFilter */
                $this->assertSame(MultiFilter::CONNECTION_OR, $salesChannelIdOrNullFilter->getOperator());

                $salesChannelIdFilterQueries = $salesChannelIdOrNullFilter->getQueries();
                $this->assertCount(2, $salesChannelIdFilterQueries);

                $salesChannelIdNullFilter = array_values($salesChannelIdFilterQueries)[0];
                $this->assertInstanceOf(EqualsFilter::class, $salesChannelIdNullFilter);
                /** @var EqualsFilter $salesChannelIdNullFilter */
                $this->assertSame('salesChannelId', $salesChannelIdNullFilter->getField());
                $this->assertNull($salesChannelIdNullFilter->getValue());

                $salesChannelIdFilter = array_values($salesChannelIdFilterQueries)[1];
                $this->assertInstanceOf(EqualsFilter::class, $salesChannelIdFilter);
                /** @var EqualsFilter $salesChannelIdFilter */
                $this->assertSame('salesChannelId', $salesChannelIdFilter->getField());
                $this->assertSame($salesChannelId, $salesChannelIdFilter->getValue());

                if ($loadSalesChannel) {
                    $this->assertCount(1, $criteriaArgument->getAssociations());
                    $this->assertTrue($criteriaArgument->hasAssociation('salesChannel'));
                } else {
                    $this->assertCount(0, $criteriaArgument->getAssociations());
                }

                $this->assertEquals($context, $contextArgument);

                return new EntitySearchResult(
                    CheckboxListingFilterConfigurationDefinition::ENTITY_NAME,
                    0,
                    $checkboxListingFilterConfigurationCollection,
                    null,
                    $criteriaArgument,
                    $contextArgument
                );
            });

        $listingFilterConfigurationRepository = new ListingFilterConfigurationRepository(
            $checkboxListingFilterConfigurationRepository,
            $this->createStub(EntityRepository::class),
            $this->createStub(EntityRepository::class),
            $this->createStub(EntityRepository::class),
        );

        $listingFilterConfigurationRepository->getCheckboxListingFilterConfigurations($context, $salesChannelId, $loadSalesChannel);
    }

    #[DataProvider('getFilterConfigurationsProvider')]
    public function testGetMultiSelectListingFilterConfigurations(string $salesChannelId, bool $loadSalesChannel): void
    {
        $context = $this->createStub(Context::class);
        $multiSelectListingFilterConfigurationCollection = $this->createMock(MultiSelectListingFilterConfigurationCollection::class);

        $multiSelectListingFilterConfigurationRepository = $this->createMock(EntityRepository::class);
        $multiSelectListingFilterConfigurationRepository->method('search')
            ->willReturnCallback(function (Criteria $criteriaArgument, Context $contextArgument) use (
                $salesChannelId,
                $loadSalesChannel,
                $context,
                $multiSelectListingFilterConfigurationCollection
            ): EntitySearchResult {
                $criteriaArgumentFilters = $criteriaArgument->getFilters();
                $this->assertCount(1, $criteriaArgumentFilters);

                $salesChannelIdOrNullFilter = array_values($criteriaArgumentFilters)[0];
                $this->assertInstanceOf(MultiFilter::class, $salesChannelIdOrNullFilter);
                /** @var MultiFilter $salesChannelIdOrNullFilter */
                $this->assertSame(MultiFilter::CONNECTION_OR, $salesChannelIdOrNullFilter->getOperator());

                $salesChannelIdFilterQueries = $salesChannelIdOrNullFilter->getQueries();
                $this->assertCount(2, $salesChannelIdFilterQueries);

                $salesChannelIdNullFilter = array_values($salesChannelIdFilterQueries)[0];
                $this->assertInstanceOf(EqualsFilter::class, $salesChannelIdNullFilter);
                /** @var EqualsFilter $salesChannelIdNullFilter */
                $this->assertSame('salesChannelId', $salesChannelIdNullFilter->getField());
                $this->assertNull($salesChannelIdNullFilter->getValue());

                $salesChannelIdFilter = array_values($salesChannelIdFilterQueries)[1];
                $this->assertInstanceOf(EqualsFilter::class, $salesChannelIdFilter);
                /** @var EqualsFilter $salesChannelIdFilter */
                $this->assertSame('salesChannelId', $salesChannelIdFilter->getField());
                $this->assertSame($salesChannelId, $salesChannelIdFilter->getValue());

                if ($loadSalesChannel) {
                    $this->assertCount(1, $criteriaArgument->getAssociations());
                    $this->assertTrue($criteriaArgument->hasAssociation('salesChannel'));
                } else {
                    $this->assertCount(0, $criteriaArgument->getAssociations());
                }

                $this->assertEquals($context, $contextArgument);

                return new EntitySearchResult(
                    MultiSelectListingFilterConfigurationDefinition::ENTITY_NAME,
                    0,
                    $multiSelectListingFilterConfigurationCollection,
                    null,
                    $criteriaArgument,
                    $contextArgument
                );
            });

        $listingFilterConfigurationRepository = new ListingFilterConfigurationRepository(
            $this->createStub(EntityRepository::class),
            $multiSelectListingFilterConfigurationRepository,
            $this->createStub(EntityRepository::class),
            $this->createStub(EntityRepository::class)
        );

        $listingFilterConfigurationRepository->getMultiSelectListingFilterConfigurations($context, $salesChannelId, $loadSalesChannel);
    }

    #[DataProvider('getFilterConfigurationsProvider')]
    public function testGetRangeIntervalListingFilterConfigurations(string $salesChannelId, bool $loadSalesChannel): void
    {
        $context = $this->createStub(Context::class);
        $rangeIntervalListingFilterConfigurationCollection = $this->createMock(RangeIntervalListingFilterConfigurationCollection::class);

        $rangeIntervalListingFilterConfigurationRepository = $this->createMock(EntityRepository::class);
        $rangeIntervalListingFilterConfigurationRepository->method('search')
            ->willReturnCallback(function (Criteria $criteriaArgument, Context $contextArgument) use (
                $salesChannelId,
                $loadSalesChannel,
                $context,
                $rangeIntervalListingFilterConfigurationCollection
            ): EntitySearchResult {
                $criteriaArgumentFilters = $criteriaArgument->getFilters();
                $this->assertCount(1, $criteriaArgumentFilters);

                $salesChannelIdOrNullFilter = array_values($criteriaArgumentFilters)[0];
                $this->assertInstanceOf(MultiFilter::class, $salesChannelIdOrNullFilter);
                /** @var MultiFilter $salesChannelIdOrNullFilter */
                $this->assertSame(MultiFilter::CONNECTION_OR, $salesChannelIdOrNullFilter->getOperator());

                $salesChannelIdFilterQueries = $salesChannelIdOrNullFilter->getQueries();
                $this->assertCount(2, $salesChannelIdFilterQueries);

                $salesChannelIdNullFilter = array_values($salesChannelIdFilterQueries)[0];
                $this->assertInstanceOf(EqualsFilter::class, $salesChannelIdNullFilter);
                /** @var EqualsFilter $salesChannelIdNullFilter */
                $this->assertSame('salesChannelId', $salesChannelIdNullFilter->getField());
                $this->assertNull($salesChannelIdNullFilter->getValue());

                $salesChannelIdFilter = array_values($salesChannelIdFilterQueries)[1];
                $this->assertInstanceOf(EqualsFilter::class, $salesChannelIdFilter);
                /** @var EqualsFilter $salesChannelIdFilter */
                $this->assertSame('salesChannelId', $salesChannelIdFilter->getField());
                $this->assertSame($salesChannelId, $salesChannelIdFilter->getValue());

                if ($loadSalesChannel) {
                    $this->assertCount(2, $criteriaArgument->getAssociations());
                    $this->assertTrue($criteriaArgument->hasAssociation('salesChannel'));
                } else {
                    $this->assertCount(1, $criteriaArgument->getAssociations());
                }

                $this->assertEquals($context, $contextArgument);

                return new EntitySearchResult(
                    RangeListingFilterConfigurationDefinition::ENTITY_NAME,
                    0,
                    $rangeIntervalListingFilterConfigurationCollection,
                    null,
                    $criteriaArgument,
                    $contextArgument
                );
            });

        $listingFilterConfigurationRepository = new ListingFilterConfigurationRepository(
            $this->createStub(EntityRepository::class),
            $this->createStub(EntityRepository::class),
            $this->createStub(EntityRepository::class),
            $rangeIntervalListingFilterConfigurationRepository
        );

        $listingFilterConfigurationRepository->getRangeIntervalListingFilterConfigurations($context, $salesChannelId, $loadSalesChannel);
    }

    #[DataProvider('getFilterConfigurationsProvider')]
    public function testGetRangeListingFilterConfigurations(string $salesChannelId, bool $loadSalesChannel): void
    {
        $context = $this->createStub(Context::class);
        $rangeListingFilterConfigurationCollection = $this->createMock(RangeListingFilterConfigurationCollection::class);

        $rangeListingFilterConfigurationRepository = $this->createMock(EntityRepository::class);
        $rangeListingFilterConfigurationRepository->method('search')
            ->willReturnCallback(function (Criteria $criteriaArgument, Context $contextArgument) use (
                $salesChannelId,
                $loadSalesChannel,
                $context,
                $rangeListingFilterConfigurationCollection
            ): EntitySearchResult {
                $criteriaArgumentFilters = $criteriaArgument->getFilters();
                $this->assertCount(1, $criteriaArgumentFilters);

                $salesChannelIdOrNullFilter = array_values($criteriaArgumentFilters)[0];
                $this->assertInstanceOf(MultiFilter::class, $salesChannelIdOrNullFilter);
                /** @var MultiFilter $salesChannelIdOrNullFilter */
                $this->assertSame(MultiFilter::CONNECTION_OR, $salesChannelIdOrNullFilter->getOperator());

                $salesChannelIdFilterQueries = $salesChannelIdOrNullFilter->getQueries();
                $this->assertCount(2, $salesChannelIdFilterQueries);

                $salesChannelIdNullFilter = array_values($salesChannelIdFilterQueries)[0];
                $this->assertInstanceOf(EqualsFilter::class, $salesChannelIdNullFilter);
                /** @var EqualsFilter $salesChannelIdNullFilter */
                $this->assertSame('salesChannelId', $salesChannelIdNullFilter->getField());
                $this->assertNull($salesChannelIdNullFilter->getValue());

                $salesChannelIdFilter = array_values($salesChannelIdFilterQueries)[1];
                $this->assertInstanceOf(EqualsFilter::class, $salesChannelIdFilter);
                /** @var EqualsFilter $salesChannelIdFilter */
                $this->assertSame('salesChannelId', $salesChannelIdFilter->getField());
                $this->assertSame($salesChannelId, $salesChannelIdFilter->getValue());

                if ($loadSalesChannel) {
                    $this->assertCount(1, $criteriaArgument->getAssociations());
                    $this->assertTrue($criteriaArgument->hasAssociation('salesChannel'));
                } else {
                    $this->assertCount(0, $criteriaArgument->getAssociations());
                }

                $this->assertEquals($context, $contextArgument);

                return new EntitySearchResult(
                    RangeListingFilterConfigurationDefinition::ENTITY_NAME,
                    0,
                    $rangeListingFilterConfigurationCollection,
                    null,
                    $criteriaArgument,
                    $contextArgument
                );
            });

        $listingFilterConfigurationRepository = new ListingFilterConfigurationRepository(
            $this->createStub(EntityRepository::class),
            $this->createStub(EntityRepository::class),
            $rangeListingFilterConfigurationRepository,
            $this->createStub(EntityRepository::class)
        );

        $listingFilterConfigurationRepository->getRangeListingFilterConfigurations($context, $salesChannelId, $loadSalesChannel);
    }
}
