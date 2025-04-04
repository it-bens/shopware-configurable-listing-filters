<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;

abstract class AbstractCreateListingFilterConfigurationCommand extends Command
{
    protected function configure(): void
    {
        $this->addArgument('dalField', InputArgument::REQUIRED, 'The full path to the DAL field')
            ->addArgument('displayName', InputArgument::REQUIRED, 'The display name of the filter in the default language')
            ->addOption('salesChannelId', null, InputOption::VALUE_REQUIRED, 'The sales channel ID the filter is assigned to')
            ->addOption('enabled', null, InputOption::VALUE_NONE, 'Whether the filter is enabled or not')
            ->addOption('position', null, InputOption::VALUE_REQUIRED, 'The position of the filter in the list', null)
            ->addOption(
                'twigTemplate',
                null,
                InputOption::VALUE_REQUIRED,
                'The twig template for the filter',
                $this->getDefaultTwigTemplate()
            );
    }

    protected function getDalField(InputInterface $input): string
    {
        $dalField = $input->getArgument('dalField');
        if (! is_string($dalField)) {
            throw new \InvalidArgumentException('The dalField argument must be a string.');
        }

        return $dalField;
    }

    abstract protected function getDefaultTwigTemplate(): string;

    protected function getDisplayName(InputInterface $input): string
    {
        $displayName = $input->getArgument('displayName');
        if (! is_string($displayName)) {
            throw new \InvalidArgumentException('The displayName argument must be a string.');
        }

        return $displayName;
    }

    protected function getEnabled(InputInterface $input): bool
    {
        $enabled = $input->getOption('enabled');
        if (! is_bool($enabled)) {
            throw new \InvalidArgumentException('The enabled option must be a boolean. This should not happen.');
        }

        return $enabled;
    }

    protected function getPosition(InputInterface $input): ?int
    {
        $position = $input->getOption('position');
        if ($position !== null && ! is_int($position)) {
            throw new \InvalidArgumentException('The position option must be an integer.');
        }

        return $position;
    }

    protected function getSalesChannelId(InputInterface $input): ?string
    {
        $salesChannelId = $input->getOption('salesChannelId');
        if ($salesChannelId !== null && ! is_string($salesChannelId)) {
            throw new \InvalidArgumentException('The salesChannelId option must be a string.');
        }

        return $salesChannelId;
    }

    protected function getTwigTemplate(InputInterface $input): string
    {
        $twigTemplate = $input->getOption('twigTemplate');
        if (! is_string($twigTemplate)) {
            throw new \InvalidArgumentException('The twigTemplate option must be a string. This should not happen.');
        }

        return $twigTemplate;
    }
}
