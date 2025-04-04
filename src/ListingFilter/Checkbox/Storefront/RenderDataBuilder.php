<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\ListingFilter\Checkbox\Storefront;

use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Checkbox\CheckboxListingFilterConfigurationEntity;
use Symfony\Contracts\Translation\TranslatorInterface;

final class RenderDataBuilder implements RenderDataBuilderInterface
{
    public function __construct(
        private readonly TranslatorInterface $translator
    ) {
    }

    public function buildRenderData(CheckboxListingFilterConfigurationEntity $configurationEntity): RenderData
    {
        return new RenderData(
            $configurationEntity->getTwigTemplate(),
            $configurationEntity->getFilterName(),
            $configurationEntity->getDisplayName(),
            $this->translator->trans('listing.disabledFilterTooltip')
        );
    }
}
