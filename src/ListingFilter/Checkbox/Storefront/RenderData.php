<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\ListingFilter\Checkbox\Storefront;

final class RenderData
{
    public function __construct(
        public readonly string $twigTemplate,
        private readonly string $name,
        private readonly string $displayName,
        private readonly string $disabledFilterTooltip
    ) {
    }

    /**
     * @api
     */
    public function shouldBeRendered(): bool
    {
        return true;
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
     *     }
     * }
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'displayName' => $this->displayName,
            'dataPluginSelectorOptions' => [
                'name' => $this->name,
                'snippets' => [
                    'disabledFilterText' => $this->disabledFilterTooltip,
                ],
            ],
        ];
    }
}
