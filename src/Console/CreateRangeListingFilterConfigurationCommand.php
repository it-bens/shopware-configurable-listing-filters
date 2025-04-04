<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Console;

use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Range\RangeListingFilterConfigurationCollection;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Range\RangeListingFilterConfigurationEntity;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand('itb:listing-filter:create-range-filter-configuration', 'Create a range filter configuration')]
final class CreateRangeListingFilterConfigurationCommand extends AbstractCreateListingFilterConfigurationCommand
{
    /**
     * @param EntityRepository<RangeListingFilterConfigurationCollection> $rangeListingFilterConfigurationRepository
     */
    public function __construct(
        private readonly EntityRepository $rangeListingFilterConfigurationRepository,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        parent::configure();

        $this->addOption('unit', null, InputOption::VALUE_REQUIRED, 'The unit of the range filter in the default language', null);
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

        $unit = $input->getOption('unit');
        if ($unit !== null && ! is_string($unit)) {
            $io->error('The unit option must be a string.');
            return Command::FAILURE;
        }

        $this->rangeListingFilterConfigurationRepository->create([
            [
                'dalField' => $dalField,
                'displayName' => $displayName,
                'twigTemplate' => $twigTemplate,
                'salesChannelId' => $salesChannelId,
                'enabled' => $enabled,
                'position' => $position,
                'unit' => $unit,
            ],
        ], Context::createDefaultContext());

        return Command::SUCCESS;
    }

    protected function getDefaultTwigTemplate(): string
    {
        return RangeListingFilterConfigurationEntity::TWIG_TEMPLATE;
    }
}
