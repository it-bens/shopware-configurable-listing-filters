<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\ListingFilter\MultiSelect\Storefront;

final class ElementTranslation
{
    public function __construct(
        private readonly string $text
    ) {
    }

    /**
     * .name is used by the default twig template for multi-select filters for the text
     *
     * @api
     */
    public function getName(): string
    {
        return $this->text;
    }
}
