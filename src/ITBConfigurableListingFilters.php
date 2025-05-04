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
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\RangeInterval\Aggregate\Interval\Aggregate\RangeIntervalListingFilterConfigurationIntervalTranslationDefinition;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\RangeInterval\Aggregate\Interval\RangeIntervalListingFilterConfigurationIntervalDefinition;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\RangeInterval\Aggregate\Translation\RangeIntervalListingFilterConfigurationTranslationDefinition;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\RangeInterval\RangeIntervalListingFilterConfigurationDefinition;
use Shopware\Core\Framework\Plugin;
use Shopware\Core\Framework\Plugin\Context\UninstallContext;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Loader\DelegatingLoader;
use Symfony\Component\Config\Loader\LoaderResolver;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\DirectoryLoader;
use Symfony\Component\DependencyInjection\Loader\GlobFileLoader;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

final class ITBConfigurableListingFilters extends Plugin
{
    public const PLUGIN_NAME = 'ITBConfigurableListingFilters';

    /**
     * @codeCoverageIgnore
     */
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $loader = new PhpFileLoader($container, new FileLocator(__DIR__ . '/DependencyInjection'));
        $loader->load('services.php');

        $locator = new FileLocator('Resources/config');
        $resolver = new LoaderResolver([
            new YamlFileLoader($container, $locator),
            new GlobFileLoader($container, $locator),
            new DirectoryLoader($container, $locator),
        ]);

        $configLoader = new DelegatingLoader($resolver);
        $confDir = \rtrim($this->getPath(), '/') . '/Resources/config';
        $configLoader->load($confDir . '/{packages}/*.yaml', 'glob');
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
            RangeIntervalListingFilterConfigurationIntervalTranslationDefinition::ENTITY_NAME,
            RangeIntervalListingFilterConfigurationIntervalDefinition::ENTITY_NAME,
            RangeIntervalListingFilterConfigurationTranslationDefinition::ENTITY_NAME,
            RangeIntervalListingFilterConfigurationDefinition::ENTITY_NAME,
        ];

        foreach ($entityNames as $entityName) {
            $connection->executeStatement(\sprintf('DROP TABLE IF EXISTS `%s`', $entityName));
        }
    }
}
