<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\ListingFilter\MultiSelect\Storefront;

final class RenderData
{
    /**
     * @codeCoverageIgnore
     */
    public function __construct(
        public readonly string $twigTemplate,
        private readonly string $name,
        private readonly string $displayName,
        private readonly string $pluginSelector,
        private readonly ElementCollection $elements,
        private readonly string $disabledFilterTooltip
    ) {
    }

    /**
     * @api
     */
    public function shouldBeRendered(): bool
    {
        return $this->elements->isEmpty() === false;
    }

    /**
     * @api
     *
     * @return array{
     *     name: string,
     *     displayName: string,
     *     pluginSelector: string,
     *     dataPluginSelectorOptions: array{
     *         name: string,
     *         displayName: string,
     *         snippets: array{
     *             disabledFilterText: string
     *         }
     *     },
     *     elements: array<Element>,
     * }
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'displayName' => $this->displayName,
            'pluginSelector' => $this->pluginSelector,
            'dataPluginSelectorOptions' => [
                'name' => $this->name,
                'displayName' => $this->displayName,
                'snippets' => [
                    'disabledFilterText' => $this->disabledFilterTooltip,
                ],
            ],
            'elements' => $this->elements->getElements(),
        ];
    }
}
