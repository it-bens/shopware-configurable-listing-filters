<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Test\PHPUnit\Integration;

use ITB\ITBConfigurableListingFilters\Core\Content\Cms\ProductListingPageSubscriber;
use ITB\ITBConfigurableListingFilters\Core\Content\Product\SalesChannel\Listing\ProductListingSubscriber;
use ITB\ITBConfigurableListingFilters\Core\Framework\Adapter\Cache\CacheInvalidationSubscriber;
use ITB\ITBConfigurableListingFilters\Test\PHPUnit\Integration\TestKernel\TestKernel;
use Shopware\Core\Framework\Test\TestCaseBase\KernelLifecycleManager;
use Shopware\Core\Framework\Test\TestCaseBase\KernelTestBehaviour;
use Zalas\PHPUnit\Globals\Attribute\Env;

final class SubscriberRegistrationTest extends AbstractIntegrationTestCase
{
    use KernelTestBehaviour;

    #[Env('KERNEL_CLASS', TestKernel::class)]
    public function testSubscribersAreInstantiatable(): void
    {
        $separateKernel = KernelLifecycleManager::createKernel(
            TestKernel::class,
            true,
            '3e5661a42a764c288af287358b050d12',
            $this->getKernel()
                ->getProjectDir()
        );
        $separateKernel->boot();

        $container = $separateKernel->getContainer();

        $productListingPageSubscriber = $container->get(ProductListingPageSubscriber::class);
        $this->assertInstanceOf(ProductListingPageSubscriber::class, $productListingPageSubscriber);
        $productListingSubscriber = $container->get(ProductListingSubscriber::class);
        $this->assertInstanceOf(ProductListingSubscriber::class, $productListingSubscriber);
        $cacheInvalidationSubscriber = $container->get(CacheInvalidationSubscriber::class);
        $this->assertInstanceOf(CacheInvalidationSubscriber::class, $cacheInvalidationSubscriber);
    }
}
