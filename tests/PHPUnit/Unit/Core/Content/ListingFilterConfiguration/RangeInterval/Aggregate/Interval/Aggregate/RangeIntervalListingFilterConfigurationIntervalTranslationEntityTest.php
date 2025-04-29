<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Test\PHPUnit\Unit\Core\Content\ListingFilterConfiguration\RangeInterval\Aggregate\Interval\Aggregate;

use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\RangeInterval\Aggregate\Interval\Aggregate\RangeIntervalListingFilterConfigurationIntervalTranslationEntity;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\RangeInterval\Aggregate\Interval\RangeIntervalListingFilterConfigurationIntervalEntity;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Shopware\Core\Framework\Uuid\Uuid;

#[CoversClass(RangeIntervalListingFilterConfigurationIntervalTranslationEntity::class)]
final class RangeIntervalListingFilterConfigurationIntervalTranslationEntityTest extends TestCase
{
    public function testGetterAndSetterMethods(): void
    {
        $entity = new RangeIntervalListingFilterConfigurationIntervalTranslationEntity();

        $intervalId = Uuid::randomHex();
        $intervalEntity = new RangeIntervalListingFilterConfigurationIntervalEntity(); // Simple instance is sufficient
        $title = 'Test Title';

        $entity->setItbLfcRangeIntervalIntervalId($intervalId);
        $entity->setItbRangeIntervalInterval($intervalEntity);
        $entity->setTitle($title);

        $this->assertSame($intervalId, $entity->getItbLfcRangeIntervalIntervalId());
        $this->assertSame($intervalEntity, $entity->getItbRangeIntervalInterval());
        $this->assertSame($title, $entity->getTitle());

        $entity->setTitle(null);
        $this->assertNull($entity->getTitle());
    }
}
