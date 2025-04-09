<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Test\PHPUnit\Unit\ListingFilter\MultiSelect\Storefront;

use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\MultiSelect\MultiSelectListingFilterConfigurationEntity;
use ITB\ITBConfigurableListingFilters\ListingFilter\MultiSelect\Storefront\Element;
use ITB\ITBConfigurableListingFilters\ListingFilter\MultiSelect\Storefront\ElementsExtractor;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use Shopware\Core\Framework\DataAbstractionLayer\Search\AggregationResult\AggregationResultCollection;
use Shopware\Core\Framework\DataAbstractionLayer\Search\AggregationResult\Bucket\Bucket;
use Shopware\Core\Framework\DataAbstractionLayer\Search\AggregationResult\Bucket\BucketResult;
use Shopware\Core\Framework\DataAbstractionLayer\Search\AggregationResult\Metric\CountResult;

#[CoversClass(ElementsExtractor::class)]
final class ElementsExtractorTest extends TestCase
{
    /**
     * @return \Generator<string, array{ElementsExtractor, MultiSelectListingFilterConfigurationEntity, AggregationResultCollection, array<Element>}, mixed, void>
     */
    public static function extractElementsSuccessProvider(): \Generator
    {
        $elementsExtractor = new ElementsExtractor();

        $configurationEntity = new MultiSelectListingFilterConfigurationEntity();
        $configurationEntity->setDalField('color');

        $buckets = [new Bucket('red', 5, null), new Bucket('blue', 3, null), new Bucket('green', 2, null)];
        $bucketResult = new BucketResult('color', $buckets);
        $aggregationResults = self::createStub(AggregationResultCollection::class);
        $aggregationResults->method('get')
            ->willReturn($bucketResult);

        $expectedElements = [new Element('red', 'red'), new Element('blue', 'blue'), new Element('green', 'green')];

        yield 'without prefix and suffix' => [$elementsExtractor, $configurationEntity, $aggregationResults, $expectedElements];

        $configurationWithPrefixSuffix = new MultiSelectListingFilterConfigurationEntity();
        $configurationWithPrefixSuffix->setDalField('material');
        $configurationWithPrefixSuffix->setElementPrefix('Material: ');
        $configurationWithPrefixSuffix->setElementSuffix(' (available)');

        $buckets = [new Bucket('wood', 10, null), new Bucket('metal', 8, null), new Bucket('plastic', 15, null)];
        $bucketResult = new BucketResult('material', $buckets);
        $aggregationResults = self::createStub(AggregationResultCollection::class);
        $aggregationResults->method('get')
            ->willReturn($bucketResult);

        $expectedElements = [
            new Element('wood', 'Material: wood (available)'),
            new Element('metal', 'Material: metal (available)'),
            new Element('plastic', 'Material: plastic (available)'),
        ];

        yield 'with prefix and suffix' => [
            $elementsExtractor,
            $configurationWithPrefixSuffix,
            $aggregationResults,
            $expectedElements,
        ];

        $configurationEmpty = new MultiSelectListingFilterConfigurationEntity();
        $configurationEmpty->setDalField('size');

        $bucketResult = new BucketResult('size', []);
        $aggregationResults = self::createStub(AggregationResultCollection::class);
        $aggregationResults->method('get')
            ->willReturn($bucketResult);

        yield 'without buckets' => [$elementsExtractor, $configurationEmpty, $aggregationResults, []];

        $configurationWithNulls = new MultiSelectListingFilterConfigurationEntity();
        $configurationWithNulls->setDalField('tags');

        $buckets = [new Bucket('tag1', 5, null), new Bucket(null, 3, null), new Bucket('', 2, null), new Bucket('tag2', 1, null)];
        $bucketResult = new BucketResult('tags', $buckets);
        $aggregationResults = self::createStub(AggregationResultCollection::class);
        $aggregationResults->method('get')
            ->willReturn($bucketResult);

        $expectedElements = [new Element('tag1', 'tag1'), new Element('tag2', 'tag2')];

        yield 'with bucket with null and blank keys' => [
            $elementsExtractor,
            $configurationWithNulls,
            $aggregationResults,
            $expectedElements,
        ];
    }

    public static function extractElementsWithNotBucketAggregationResultProvider(): \Generator
    {
        $elementsExtractor = new ElementsExtractor();

        $configurationEntity = new MultiSelectListingFilterConfigurationEntity();
        $configurationEntity->setDalField('color');

        $aggregationResults = self::createStub(AggregationResultCollection::class);
        $aggregationResult = self::createStub(CountResult::class);
        $aggregationResults->method('get')
            ->willReturn($aggregationResult);

        yield [$elementsExtractor, $configurationEntity, $aggregationResults];
    }

    public static function extractElementsWithoutAggregationResultProvider(): \Generator
    {
        $elementsExtractor = new ElementsExtractor();

        $configurationEntity = new MultiSelectListingFilterConfigurationEntity();
        $configurationEntity->setDalField('color');

        $aggregationResults = self::createStub(AggregationResultCollection::class);
        $aggregationResults->method('get')
            ->willReturn(null);

        yield [$elementsExtractor, $configurationEntity, $aggregationResults];
    }

    /**
     * @param array<Element> $expectedElements
     */
    #[DataProvider('extractElementsSuccessProvider')]
    public function testExtractElementsFromAggregations(
        ElementsExtractor $elementsExtractor,
        MultiSelectListingFilterConfigurationEntity $configurationEntity,
        AggregationResultCollection $aggregationResults,
        array $expectedElements
    ): void {
        $elements = $elementsExtractor->extractElementsFromAggregations($configurationEntity, $aggregationResults);

        $this->assertCount(count($expectedElements), $elements);

        foreach ($elements as $index => $element) {
            $this->assertInstanceOf(Element::class, $element);
            $this->assertSame($expectedElements[$index]->name, $element->name);
            $this->assertSame($expectedElements[$index]->text, $element->text);
        }
    }

    #[DataProvider('extractElementsWithNotBucketAggregationResultProvider')]
    public function testExtractElementsWithNotBucketAggregationResult(
        ElementsExtractor $elementsExtractor,
        MultiSelectListingFilterConfigurationEntity $configurationEntity,
        AggregationResultCollection $aggregationResults
    ): void {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(
            sprintf('The aggregation result "%s" is not a bucket result.', $configurationEntity->getAggregationName())
        );
        $elementsExtractor->extractElementsFromAggregations($configurationEntity, $aggregationResults);
    }

    #[DataProvider('extractElementsWithoutAggregationResultProvider')]
    public function testExtractElementsWithoutAggregationResult(
        ElementsExtractor $elementsExtractor,
        MultiSelectListingFilterConfigurationEntity $configurationEntity,
        AggregationResultCollection $aggregationResults
    ): void {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(sprintf('The aggregation "%s" could not be found.', $configurationEntity->getAggregationName()));
        $elementsExtractor->extractElementsFromAggregations($configurationEntity, $aggregationResults);
    }
}
