<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Test\PHPUnit\Unit\Core\Content\ListingFilterConfiguration\Checkbox\Aggregate;

use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Checkbox\Aggregate\CheckboxListingFilterConfigurationTranslationEntity;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Checkbox\CheckboxListingFilterConfigurationEntity;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(CheckboxListingFilterConfigurationTranslationEntity::class)]
final class CheckboxListingFilterConfigurationTranslationEntityTest extends TestCase
{
    public function testGetterAndSetterMethods(): void
    {
        $entity = new CheckboxListingFilterConfigurationTranslationEntity();

        $displayName = 'Test Display Name';
        $entity->setDisplayName($displayName);

        $checkboxConfig = new CheckboxListingFilterConfigurationEntity();
        $checkboxConfigId = 'test-id';

        $entity->setCheckboxListingFilterConfiguration($checkboxConfig);
        $entity->setCheckboxListingFilterConfigurationId($checkboxConfigId);

        $this->assertSame($displayName, $entity->getDisplayName());
        $this->assertEquals($checkboxConfig, $entity->getCheckboxListingFilterConfiguration());
        $this->assertSame($checkboxConfigId, $entity->getCheckboxListingFilterConfigurationId());
    }
}
