<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Test\PHPUnit\Integration;

use ITB\ITBConfigurableListingFilters\Core\Content\Cms\ProductListingPageSubscriber;
use ITB\ITBConfigurableListingFilters\Core\Content\Product\SalesChannel\Listing\ProductListingSubscriber;
use ITB\ITBConfigurableListingFilters\Test\PHPUnit\Integration\TestKernel\TestKernel;
use PHPUnit\Framework\TestCase;
use Shopware\Core\Framework\Adapter\Kernel\KernelFactory;
use Shopware\Core\Framework\Test\TestCaseBase\KernelLifecycleManager;
use Shopware\Core\Framework\Test\TestCaseBase\KernelTestBehaviour;

final class SubscriberRegistrationTest extends TestCase
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
    }
}
