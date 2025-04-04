<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Console;

use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\MultiSelect\MultiSelectListingFilterConfigurationCollection;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\MultiSelect\MultiSelectListingFilterConfigurationEntity;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand('itb:listing-filter:create-multi-select-filter-configuration', 'Create a multi-select filter configuration')]
final class CreateMultiSelectListingFilterConfigurationCommand extends AbstractCreateListingFilterConfigurationCommand
{
    /**
     * @param EntityRepository<MultiSelectListingFilterConfigurationCollection> $multiSelectListingFilterConfigurationRepository
     */
    public function __construct(
        private readonly EntityRepository $multiSelectListingFilterConfigurationRepository,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        parent::configure();

        $this->addOption(
            'sortingOrder',
            null,
            InputOption::VALUE_REQUIRED,
            'The sorting order of the filter. Can be "asc" or "desc"',
            'asc'
        )
            ->addOption(
                'explicitElementSorting',
                null,
                InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY,
                'The explicit element sorting for the filter'
            )
            ->addOption(
                'allowedElements',
                null,
                InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY,
                'The allowed elements for the filter'
            )
            ->addOption(
                'forbiddenElements',
                null,
                InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY,
                'The forbidden elements for the filter'
            )
            ->addOption('elementPrefix', null, InputOption::VALUE_REQUIRED, 'The prefix for the filter elements')
            ->addOption('elementSuffix', null, InputOption::VALUE_REQUIRED, 'The suffix for the filter elements');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        try {
            $dalField = $this->getDalField($input);
            $displayName = $this->getDisplayName($input);
            $salesChannelId = $this->getSalesChannelId($input);
            $enabled = $this->getEnabled($input);
            $position = $this->getPosition($input);
            $twigTemplate = $this->getTwigTemplate($input);
        } catch (\InvalidArgumentException $invalidArgumentException) {
            $io->error($invalidArgumentException->getMessage());
            return Command::FAILURE;
        }

        $sortingOrder = $input->getOption('sortingOrder');
        if (! is_string($sortingOrder)) {
            $io->error('The sortingOrder option must be a string. This should not happen.');
            return Command::FAILURE;
        }

        $explicitElementSorting = $input->getOption('explicitElementSorting');
        if ($explicitElementSorting !== null && ! is_array($explicitElementSorting)) {
            $io->error('The explicitElementSorting option must be an array.');
            return Command::FAILURE;
        }

        $allowedElements = $input->getOption('allowedElements');
        if ($allowedElements !== null && ! is_array($allowedElements)) {
            $io->error('The allowedElements option must be an array.');
            return Command::FAILURE;
        }

        $forbiddenElements = $input->getOption('forbiddenElements');
        if ($forbiddenElements !== null && ! is_array($forbiddenElements)) {
            $io->error('The forbiddenElements option must be an array.');
            return Command::FAILURE;
        }

        $elementPrefix = $input->getOption('elementPrefix');
        if ($elementPrefix !== null && ! is_string($elementPrefix)) {
            $io->error('The elementPrefix option must be a string.');
            return Command::FAILURE;
        }

        $elementSuffix = $input->getOption('elementSuffix');
        if ($elementSuffix !== null && ! is_string($elementSuffix)) {
            $io->error('The elementSuffix option must be a string.');
            return Command::FAILURE;
        }

        $this->multiSelectListingFilterConfigurationRepository->create([
            [
                'dalField' => $dalField,
                'displayName' => $displayName,
                'twigTemplate' => $twigTemplate,
                'salesChannelId' => $salesChannelId,
                'enabled' => $enabled,
                'position' => $position,
                'sortingOrder' => $sortingOrder,
                'explicitElementSorting' => $explicitElementSorting,
                'allowedElements' => $allowedElements,
                'forbiddenElements' => $forbiddenElements,
                'elementPrefix' => $elementPrefix,
                'elementSuffix' => $elementSuffix,
            ],
        ], Context::createDefaultContext());

        return Command::SUCCESS;
    }

    protected function getDefaultTwigTemplate(): string
    {
        return MultiSelectListingFilterConfigurationEntity::TWIG_TEMPLATE;
    }
}
