<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Test\PHPUnit\Integration\TestKernel;

use ITB\ITBConfigurableListingFilters\Core\Content\Cms\ProductListingPageSubscriber;
use ITB\ITBConfigurableListingFilters\Core\Content\Product\SalesChannel\Listing\ProductListingSubscriber;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class MakeServicesPublicForTestsCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if (! $this->isPHPUnit()) {
            return;
        }

        $productListingPageSubscriberDefinition = $container->getDefinition(PRoductListingPageSubscriber::class);
        $productListingPageSubscriberDefinition->setPublic(true);

        $productListingSubscriberDefinition = $container->getDefinition(ProductListingSubscriber::class);
        $productListingSubscriberDefinition->setPublic(true);
    }

    private function isPHPUnit(): bool
    {
        // the constants are defined by PHPUnit
        return defined('PHPUNIT_COMPOSER_INSTALL') || defined('__PHPUNIT_PHAR__');
    }
}
