<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Test\PHPUnit\Unit\Core\Content\ListingFilterConfiguration\Aggregate\ListingFilterConfigurationTranslation;

use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Aggregate\ListingFilterConfigurationTranslation\ListingFilterConfigurationTranslationEntity;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(ListingFilterConfigurationTranslationEntity::class)]
final class ListingFilterConfigurationTranslationEntityTest extends TestCase
{
    public function testGetterAndSetterMethods(): void
    {
        $entity = new class() extends ListingFilterConfigurationTranslationEntity {
        };

        $displayName = 'Test Display Name';
        $entity->setDisplayName($displayName);

        $this->assertSame($displayName, $entity->getDisplayName());
    }
}
