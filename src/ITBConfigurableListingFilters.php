<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters;

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
use Shopware\Core\Framework\Plugin;
use Shopware\Core\Framework\Plugin\Context\UninstallContext;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;

final class ITBConfigurableListingFilters extends Plugin
{
    /**
     * @codeCoverageIgnore
     */
    public function build(ContainerBuilder $container): void
    {
        $loader = new PhpFileLoader($container, new FileLocator(__DIR__ . '/DependencyInjection'));
        $loader->load('services.php');

        parent::build($container);
    }

    public function uninstall(UninstallContext $uninstallContext): void
    {
        parent::uninstall($uninstallContext);

        if ($uninstallContext->keepUserData()) {
            return;
        }

        $connection = $this->container?->get(Connection::class);
        if (! $connection instanceof Connection) {
            throw new \RuntimeException('Connection instance could not be fetched from the container');
        }

        $entityNames = [
            CheckboxListingFilterConfigurationTranslationDefinition::ENTITY_NAME,
            CheckboxListingFilterConfigurationDefinition::ENTITY_NAME,
            MultiSelectListingFilterConfigurationTranslationDefinition::ENTITY_NAME,
            MultiSelectListingFilterConfigurationDefinition::ENTITY_NAME,
            RangeListingFilterConfigurationTranslationDefinition::ENTITY_NAME,
            RangeListingFilterConfigurationDefinition::ENTITY_NAME,
            RangeIntervalListingFilterConfigurationTranslationDefinition::ENTITY_NAME,
            RangeIntervalListingFilterConfigurationIntervalDefinition::ENTITY_NAME,
            RangeIntervalListingFilterConfigurationDefinition::ENTITY_NAME,
        ];

        foreach ($entityNames as $entityName) {
            $connection->executeStatement(\sprintf('DROP TABLE IF EXISTS `%s`', $entityName));
        }
    }
}
