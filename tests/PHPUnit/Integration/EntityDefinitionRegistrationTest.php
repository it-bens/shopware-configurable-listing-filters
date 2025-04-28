<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Test\PHPUnit\Integration;

use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Checkbox\Aggregate\CheckboxListingFilterConfigurationTranslationDefinition;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Checkbox\CheckboxListingFilterConfigurationDefinition;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\MultiSelect\Aggregate\MultiSelectListingFilterConfigurationTranslationDefinition;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\MultiSelect\MultiSelectListingFilterConfigurationDefinition;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Range\Aggregate\RangeListingFilterConfigurationTranslationDefinition;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Range\RangeListingFilterConfigurationDefinition;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\RangeInterval\Aggregate\RangeIntervalListingFilterConfigurationTranslationDefinition;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\RangeInterval\RangeIntervalListingFilterConfigurationDefinition;
use ITB\ITBConfigurableListingFilters\Test\PHPUnit\Integration\TestKernel\TestKernel;
use Shopware\Core\Framework\DataAbstractionLayer\DefinitionInstanceRegistry;
use Shopware\Core\Framework\Test\TestCaseBase\KernelLifecycleManager;
use Shopware\Core\Framework\Test\TestCaseBase\KernelTestBehaviour;
use Zalas\PHPUnit\Globals\Attribute\Server;

final class EntityDefinitionRegistrationTest extends AbstractIntegrationTestCase
{
    use KernelTestBehaviour;

    #[Server('KERNEL_CLASS', TestKernel::class)]
    public function testDefinitionIsRegistered(): void
    {
        $separateKernel = KernelLifecycleManager::createKernel(
            TestKernel::class,
            true,
            '4bb104076aec41a7bdbf82185a04b8fd',
            $this->getKernel()
                ->getProjectDir()
        );
        $separateKernel->boot();

        $container = $separateKernel->getContainer();

        /** @var DefinitionInstanceRegistry $registry */
        $registry = $container->get(DefinitionInstanceRegistry::class);

        $checkboxListingFilterConfigurationDefinition = $registry->getByEntityName(
            CheckboxListingFilterConfigurationDefinition::ENTITY_NAME
        );
        $this->assertInstanceOf(CheckboxListingFilterConfigurationDefinition::class, $checkboxListingFilterConfigurationDefinition);
        $this->assertCount(12, $checkboxListingFilterConfigurationDefinition->getFields());

        $checkboxListingFilterConfigurationTranslationDefinition = $registry->getByEntityName(
            CheckboxListingFilterConfigurationTranslationDefinition::ENTITY_NAME
        );
        $this->assertInstanceOf(
            CheckboxListingFilterConfigurationTranslationDefinition::class,
            $checkboxListingFilterConfigurationTranslationDefinition
        );
        $this->assertCount(7, $checkboxListingFilterConfigurationTranslationDefinition->getFields());

        $multiselectListingFilterConfigurationDefinition = $registry->getByEntityName(
            MultiSelectListingFilterConfigurationDefinition::ENTITY_NAME
        );
        $this->assertInstanceOf(MultiSelectListingFilterConfigurationDefinition::class, $multiselectListingFilterConfigurationDefinition);
        $this->assertCount(18, $multiselectListingFilterConfigurationDefinition->getFields());

        $multiselectListingFilterConfigurationTranslationDefinition = $registry->getByEntityName(
            MultiSelectListingFilterConfigurationTranslationDefinition::ENTITY_NAME
        );
        $this->assertInstanceOf(
            MultiSelectListingFilterConfigurationTranslationDefinition::class,
            $multiselectListingFilterConfigurationTranslationDefinition
        );
        $this->assertCount(12, $multiselectListingFilterConfigurationTranslationDefinition->getFields());

        $rangeListingFilterConfigurationDefinition = $registry->getByEntityName(RangeListingFilterConfigurationDefinition::ENTITY_NAME);
        $this->assertInstanceOf(RangeListingFilterConfigurationDefinition::class, $rangeListingFilterConfigurationDefinition);
        $this->assertCount(13, $rangeListingFilterConfigurationDefinition->getFields());

        $rangeListingFilterConfigurationTranslationDefinition = $registry->getByEntityName(
            RangeListingFilterConfigurationTranslationDefinition::ENTITY_NAME
        );
        $this->assertInstanceOf(
            RangeListingFilterConfigurationTranslationDefinition::class,
            $rangeListingFilterConfigurationTranslationDefinition
        );
        $this->assertCount(8, $rangeListingFilterConfigurationTranslationDefinition->getFields());

        $rangeListingFilterConfigurationDefinition = $registry->getByEntityName(
            RangeIntervalListingFilterConfigurationDefinition::ENTITY_NAME
        );
        $this->assertInstanceOf(RangeIntervalListingFilterConfigurationDefinition::class, $rangeListingFilterConfigurationDefinition);
        $this->assertCount(15, $rangeListingFilterConfigurationDefinition->getFields());

        $rangeIntervalListingFilterConfigurationTranslationDefinition = $registry->getByEntityName(
            RangeIntervalListingFilterConfigurationTranslationDefinition::ENTITY_NAME
        );
        $this->assertInstanceOf(
            RangeIntervalListingFilterConfigurationTranslationDefinition::class,
            $rangeIntervalListingFilterConfigurationTranslationDefinition
        );
        $this->assertCount(9, $rangeIntervalListingFilterConfigurationTranslationDefinition->getFields());
    }
}
