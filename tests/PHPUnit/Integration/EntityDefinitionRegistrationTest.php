<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Test\PHPUnit\Integration;

use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Checkbox\Aggregate\CheckboxListingFilterConfigurationTranslationDefinition;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Checkbox\CheckboxListingFilterConfigurationDefinition;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\MultiSelect\MultiSelectListingFilterConfigurationDefinition;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Range\RangeListingFilterConfigurationDefinition;
use ITB\ITBConfigurableListingFilters\Test\PHPUnit\Integration\TestKernel\TestKernel;
use PHPUnit\Framework\TestCase;
use Shopware\Core\Framework\Adapter\Kernel\KernelFactory;
use Shopware\Core\Framework\DataAbstractionLayer\DefinitionInstanceRegistry;
use Shopware\Core\Framework\Test\TestCaseBase\KernelLifecycleManager;
use Shopware\Core\Framework\Test\TestCaseBase\KernelTestBehaviour;

final class EntityDefinitionRegistrationTest extends TestCase
{
    use KernelTestBehaviour;

    private static string $originalKernelClass;

    public static function setUpBeforeClass(): void
    {
        self::$originalKernelClass = KernelFactory::$kernelClass;
        KernelFactory::$kernelClass = TestKernel::class;
    }

    public static function tearDownAfterClass(): void
    {
        KernelFactory::$kernelClass = self::$originalKernelClass;
    }

    public function testDefintionIsRegistered(): void
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
            MultiSelectListingFilterConfigurationDefinition::ENTITY_NAME
        );
        $this->assertInstanceOf(
            MultiSelectListingFilterConfigurationDefinition::class,
            $multiselectListingFilterConfigurationTranslationDefinition
        );
        $this->assertCount(18, $multiselectListingFilterConfigurationTranslationDefinition->getFields());

        $rangeListingFilterConfigurationDefinition = $registry->getByEntityName(RangeListingFilterConfigurationDefinition::ENTITY_NAME);
        $this->assertInstanceOf(RangeListingFilterConfigurationDefinition::class, $rangeListingFilterConfigurationDefinition);
        $this->assertCount(13, $rangeListingFilterConfigurationDefinition->getFields());

        $rangeListingFilterConfigurationTranslationDefinition = $registry->getByEntityName(
            RangeListingFilterConfigurationDefinition::ENTITY_NAME
        );
        $this->assertInstanceOf(RangeListingFilterConfigurationDefinition::class, $rangeListingFilterConfigurationTranslationDefinition);
        $this->assertCount(13, $rangeListingFilterConfigurationTranslationDefinition->getFields());
    }
}
