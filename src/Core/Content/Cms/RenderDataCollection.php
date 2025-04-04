<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Core\Content\Cms;

use ITB\ITBConfigurableListingFilters\ListingFilter\Checkbox\Storefront\RenderData as CheckboxRenderData;
use ITB\ITBConfigurableListingFilters\ListingFilter\MultiSelect\Storefront\RenderData as MultiSelectRenderData;
use ITB\ITBConfigurableListingFilters\ListingFilter\Range\Storefront\RenderData as RangeRenderData;
use Shopware\Core\Framework\Struct\Struct;

final class RenderDataCollection extends Struct
{
    public const NAME = 'itb-listing-filters-render-data-collection';

    /**
     * @var array<CheckboxRenderData|MultiSelectRenderData|RangeRenderData>
     */
    private array $renderDatasets = [];

    public function add(CheckboxRenderData|MultiSelectRenderData|RangeRenderData $renderDataset): void
    {
        $this->renderDatasets[] = $renderDataset;
    }

    /**
     * @return array<CheckboxRenderData|MultiSelectRenderData|RangeRenderData>
     */
    public function getRenderDatasets(): array
    {
        return $this->renderDatasets;
    }
}
