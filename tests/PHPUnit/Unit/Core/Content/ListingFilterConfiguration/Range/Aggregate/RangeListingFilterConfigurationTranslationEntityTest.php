<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Test\PHPUnit\Unit\Core\Content\ListingFilterConfiguration\Range\Aggregate;

use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Range\Aggregate\RangeListingFilterConfigurationTranslationEntity;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Range\RangeListingFilterConfigurationEntity;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(RangeListingFilterConfigurationTranslationEntity::class)]
final class RangeListingFilterConfigurationTranslationEntityTest extends TestCase
{
    public function testGetterAndSetterMethods(): void
    {
        $entity = new RangeListingFilterConfigurationTranslationEntity();

        $displayName = 'Test Display Name';
        $entity->setDisplayName($displayName);

        $rangeConfig = new RangeListingFilterConfigurationEntity();
        $rangeConfigId = 'test-id';
        $unit = 'kg';

        $entity->setRangeListingFilterConfiguration($rangeConfig);
        $entity->setRangeListingFilterConfigurationId($rangeConfigId);
        $entity->setUnit($unit);

        $this->assertSame($displayName, $entity->getDisplayName());
        $this->assertEquals($rangeConfig, $entity->getRangeListingFilterConfiguration());
        $this->assertSame($rangeConfigId, $entity->getRangeListingFilterConfigurationId());
        $this->assertSame($unit, $entity->getUnit());
    }
}
