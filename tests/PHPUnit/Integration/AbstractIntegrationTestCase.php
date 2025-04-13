<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Test\PHPUnit\Integration;

use ITB\ITBConfigurableListingFilters\Test\PHPUnit\Integration\TestKernel\TestKernel;
use PHPUnit\Framework\TestCase;
use Shopware\Core\Framework\Adapter\Kernel\KernelFactory;
use Shopware\Core\Kernel;

abstract class AbstractIntegrationTestCase extends TestCase
{
    /**
     * @var class-string<Kernel>
     */
    private static string $originalKernelClass;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        if (class_exists(KernelFactory::class)) {
            self::$originalKernelClass = KernelFactory::$kernelClass;
            KernelFactory::$kernelClass = TestKernel::class;
        }
    }

    public static function tearDownAfterClass(): void
    {
        parent::tearDownAfterClass();

        if (class_exists(KernelFactory::class)) {
            KernelFactory::$kernelClass = self::$originalKernelClass;
        }
    }
}
