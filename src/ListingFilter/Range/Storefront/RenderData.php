<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\ListingFilter\Range\Storefront;

final class RenderData
{
    public function __construct(
        private readonly string $twigTemplate,
        private readonly string $filterName,
        private readonly string $displayName,
        private readonly string $gteQueryParameter,
        private readonly string $lteQueryParameter,
        private readonly ?int $gteInputValue,
        private readonly ?int $lteInputValue,
        private readonly ?string $unit,
        private readonly string $disabledFilterTooltip
    ) {
    }

    /**
     * @api
     */
    public function shouldBeRendered(): bool
    {
        return $this->gteInputValue !== $this->lteInputValue;
    }

    /**
     * @return array{
     *     name: string,
     *     displayName: string,
     *     dataPluginSelectorOptions: array{
     *         name: string,
     *         snippets: array{
     *             disabledFilterText: string
     *         }
     *     },
     *     minKey: string,
     *     maxKey: string,
     *     minInputValue: int|null,
     *     maxInputValue: int|null,
     *     unit: string
     *  }
     */
    public function toArray(): array
    {
        return [
            'name' => $this->filterName,
            'displayName' => $this->displayName,
            'dataPluginSelectorOptions' => [
                'name' => $this->filterName,
                'snippets' => [
                    'disabledFilterText' => $this->disabledFilterTooltip,
                ],
            ],
            'minKey' => $this->gteQueryParameter,
            'maxKey' => $this->lteQueryParameter,
            'minInputValue' => $this->gteInputValue,
            'maxInputValue' => $this->lteInputValue,
            'unit' => $this->unit ?? '',
        ];
    }

    /**
     * @api
     */
    public function twigTemplate(): string
    {
        return $this->twigTemplate;
    }
}
