<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Test\PHPUnit\Integration\TestKernel;

use Shopware\Core\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class TestKernel extends Kernel
{
    public function registerContainerConfiguration(LoaderInterface $loader): void
    {
        parent::registerContainerConfiguration($loader);

        $loader->load(function (ContainerBuilder $container): void {
            // Tested services are made public to use them via container.
            $container->addCompilerPass(new MakeServicesPublicForTestsCompilerPass());
        });
    }
}
