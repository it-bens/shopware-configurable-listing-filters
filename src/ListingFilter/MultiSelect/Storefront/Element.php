<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\ListingFilter\MultiSelect\Storefront;

final class Element
{
    public function __construct(
        public readonly string $name,
        public readonly string $text
    ) {
    }

    /**
     * .id is used by the default twig template for multi-select filters for the option value
     *
     * @api
     */
    public function getId(): string
    {
        return $this->name;
    }

    /**
     * .translated.name is used by the default twig template for multi-select filters for the text
     *
     * @api
     */
    public function getTranslated(): ElementTranslation
    {
        return new ElementTranslation($this->text);
    }
}
