<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Test\PHPUnit\Unit\Core\Content\ListingFilterConfiguration\MultiSelect\Aggregate;

use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\MultiSelect\Aggregate\MultiSelectListingFilterConfigurationTranslationEntity;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\MultiSelect\MultiSelectListingFilterConfigurationEntity;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(MultiSelectListingFilterConfigurationTranslationEntity::class)]
final class MultiSelectListingFilterConfigurationTranslationEntityTest extends TestCase
{
    public function testGetterAndSetterMethods(): void
    {
        $entity = new MultiSelectListingFilterConfigurationTranslationEntity();

        $displayName = 'Test Display Name';
        $entity->setDisplayName($displayName);

        $multiSelectConfig = new MultiSelectListingFilterConfigurationEntity();
        $multiSelectConfigId = 'test-id';

        $allowedElements = ['element1', 'element2'];
        $elementPrefix = 'prefix';
        $elementSuffix = 'suffix';
        $explicitElementSorting = ['sort1', 'sort2'];
        $forbiddenElements = ['forbidden1', 'forbidden2'];

        $entity->setMultiSelectListingFilterConfiguration($multiSelectConfig);
        $entity->setMultiSelectListingFilterConfigurationId($multiSelectConfigId);
        $entity->setAllowedElements($allowedElements);
        $entity->setElementPrefix($elementPrefix);
        $entity->setElementSuffix($elementSuffix);
        $entity->setExplicitElementSorting($explicitElementSorting);
        $entity->setForbiddenElements($forbiddenElements);

        $this->assertSame($displayName, $entity->getDisplayName());
        $this->assertEquals($multiSelectConfig, $entity->getMultiSelectListingFilterConfiguration());
        $this->assertSame($multiSelectConfigId, $entity->getMultiSelectListingFilterConfigurationId());
        $this->assertSame($allowedElements, $entity->getAllowedElements());
        $this->assertSame($elementPrefix, $entity->getElementPrefix());
        $this->assertSame($elementSuffix, $entity->getElementSuffix());
        $this->assertSame($explicitElementSorting, $entity->getExplicitElementSorting());
        $this->assertSame($forbiddenElements, $entity->getForbiddenElements());
    }
}
