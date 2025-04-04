<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters;

use Shopware\Core\Framework\Plugin;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;

final class ITBConfigurableListingFilters extends Plugin
{
    public function build(ContainerBuilder $container): void
    {
        $loader = new PhpFileLoader($container, new FileLocator(__DIR__ . '/DependencyInjection'));
        $loader->load('services.php');

        parent::build($container);
    }
}
