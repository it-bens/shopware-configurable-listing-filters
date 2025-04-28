<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Test\PHPUnit\Unit;

use Doctrine\DBAL\Connection;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Checkbox\Aggregate\CheckboxListingFilterConfigurationTranslationDefinition;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Checkbox\CheckboxListingFilterConfigurationDefinition;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\MultiSelect\Aggregate\MultiSelectListingFilterConfigurationTranslationDefinition;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\MultiSelect\MultiSelectListingFilterConfigurationDefinition;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Range\Aggregate\RangeListingFilterConfigurationTranslationDefinition;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Range\RangeListingFilterConfigurationDefinition;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\RangeInterval\Aggregate\RangeIntervalListingFilterConfigurationIntervalDefinition;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\RangeInterval\Aggregate\RangeIntervalListingFilterConfigurationTranslationDefinition;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\RangeInterval\RangeIntervalListingFilterConfigurationDefinition;
use ITB\ITBConfigurableListingFilters\ITBConfigurableListingFilters;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Shopware\Core\Framework\Plugin\Context\UninstallContext;
use Symfony\Component\DependencyInjection\ContainerInterface;

#[CoversClass(ITBConfigurableListingFilters::class)]
final class ITBConfigurableListingFiltersTest extends TestCase
{
    private MockObject&Connection $connectionMock;

    private ITBConfigurableListingFilters $plugin;

    private MockObject&UninstallContext $uninstallContextMock;

    protected function setUp(): void
    {
        parent::setUp();
        $this->connectionMock = $this->createMock(Connection::class);
        $containerMock = $this->createMock(ContainerInterface::class);
        $this->uninstallContextMock = $this->createMock(UninstallContext::class);

        $containerMock
            ->method('get')
            ->with(Connection::class)
            ->willReturn($this->connectionMock);

        // Instantiate the plugin manually as it doesn't have a constructor dependency
        // We need reflection to set the protected container property
        $this->plugin = new ITBConfigurableListingFilters(true, '', '');
        $reflection = new \ReflectionClass($this->plugin);
        $containerProperty = $reflection->getProperty('container');
        $containerProperty->setValue($this->plugin, $containerMock);
    }

    public function testUninstallDoesNotRemoveTablesWhenKeepingUserData(): void
    {
        $this->uninstallContextMock
            ->expects($this->once())
            ->method('keepUserData')
            ->willReturn(true);

        // Expect executeStatement to never be called
        $this->connectionMock
            ->expects($this->never())
            ->method('executeStatement');

        $this->plugin->uninstall($this->uninstallContextMock);
    }

    public function testUninstallRemovesTablesWhenNotKeepingUserData(): void
    {
        $this->uninstallContextMock
            ->expects($this->once())
            ->method('keepUserData')
            ->willReturn(false);

        $expectedEntityNames = [
            CheckboxListingFilterConfigurationDefinition::ENTITY_NAME,
            CheckboxListingFilterConfigurationTranslationDefinition::ENTITY_NAME,
            MultiSelectListingFilterConfigurationDefinition::ENTITY_NAME,
            MultiSelectListingFilterConfigurationTranslationDefinition::ENTITY_NAME,
            RangeListingFilterConfigurationDefinition::ENTITY_NAME,
            RangeListingFilterConfigurationTranslationDefinition::ENTITY_NAME,
            RangeIntervalListingFilterConfigurationDefinition::ENTITY_NAME,
            RangeIntervalListingFilterConfigurationTranslationDefinition::ENTITY_NAME,
            RangeIntervalListingFilterConfigurationIntervalDefinition::ENTITY_NAME,
        ];

        // Expect executeStatement to be called exactly once for each entity
        $this->connectionMock
            ->expects($this->exactly(\count($expectedEntityNames)))
            ->method('executeStatement')
            ->willReturnOnConsecutiveCalls(
                ...array_map(static fn (string $name): array => [\sprintf('DROP TABLE IF EXISTS `%s`', $name)], $expectedEntityNames)
            );

        $this->plugin->uninstall($this->uninstallContextMock);
    }

    public function testUninstallThrowsExceptionIfConnectionNotAvailable(): void
    {
        $this->uninstallContextMock
            ->expects($this->once())
            ->method('keepUserData')
            ->willReturn(false);

        // Configure container mock to return null for Connection
        $containerMockReturningNull = $this->createMock(ContainerInterface::class);
        $containerMockReturningNull
            ->method('get')
            ->with(Connection::class)
            ->willReturn(null);

        // Set the container mock that returns null
        $reflection = new \ReflectionClass($this->plugin);
        $containerProperty = $reflection->getProperty('container');
        $containerProperty->setValue($this->plugin, $containerMockReturningNull);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Connection instance could not be fetched from the container');

        $this->plugin->uninstall($this->uninstallContextMock);
    }
}
