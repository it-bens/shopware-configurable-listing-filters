<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Console;

use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Checkbox\CheckboxListingFilterConfigurationCollection;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Checkbox\CheckboxListingFilterConfigurationEntity;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand('itb:listing-filter:create-checkbox-filter-configuration', 'Create a checkbox listing filter configuration')]
final class CreateCheckboxListingFilterConfigurationCommand extends AbstractCreateListingFilterConfigurationCommand
{
    /**
     * @param EntityRepository<CheckboxListingFilterConfigurationCollection> $checkboxListingFilterConfigurationRepository
     */
    public function __construct(
        private readonly EntityRepository $checkboxListingFilterConfigurationRepository,
    ) {
        parent::__construct();
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

        $this->checkboxListingFilterConfigurationRepository->create([
            [
                'dalField' => $dalField,
                'displayName' => $displayName,
                'twigTemplate' => $twigTemplate,
                'salesChannelId' => $salesChannelId,
                'enabled' => $enabled,
                'position' => $position,
            ],
        ], Context::createDefaultContext());

        return Command::SUCCESS;
    }

    protected function getDefaultTwigTemplate(): string
    {
        return CheckboxListingFilterConfigurationEntity::TWIG_TEMPLATE;
    }
}
