<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Test\PHPUnit\Integration;

use PHPUnit\Framework\TestCase;
use Shopware\Core\Framework\Test\TestCaseBase\KernelLifecycleManager;
use Shopware\Core\Framework\Test\TestCaseBase\KernelTestBehaviour;
use Symfony\Bundle\FrameworkBundle\Command\ContainerLintCommand;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

final class ContainerListTest extends TestCase
{
    use KernelTestBehaviour;

    /**
     * Borrowed but never returned from Shopwares ServiceDefinitionTest
     */
    public function testContainerLintCommand(): void
    {
        /** @var ContainerLintCommand $command */
        $command = $this->getContainer()
            ->get('console.command.container_lint');
        $command->setApplication(new Application(KernelLifecycleManager::getKernel()));

        $commandTester = new CommandTester($command);

        set_error_handler(fn (): bool => true, \E_USER_DEPRECATED);
        $commandTester->execute([]);
        restore_error_handler();

        $this->assertSame(
            0,
            $commandTester->getStatusCode(),
            "\"bin/console lint:container\" returned errors:\n" . $commandTester->getDisplay()
        );
    }
}
