<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\ListingFilter\MultiSelect\Storefront;

final class ElementCollection
{
    /**
     * @param Element[] $elements
     * @param array<string>|null $explicitElementSorting
     */
    public function __construct(
        private readonly array $elements,
        private readonly ?array $explicitElementSorting
    ) {
    }

    /**
     * @return Element[]
     */
    public function getElements(): array
    {
        if (! is_array($this->explicitElementSorting)) {
            return $this->elements;
        }

        $sortedElements = [];
        foreach ($this->explicitElementSorting as $elementName) {
            foreach ($this->elements as $element) {
                if ($element->getId() === $elementName) {
                    $sortedElements[] = $element;
                }
            }
        }

        foreach ($this->elements as $element) {
            if (! in_array($element, $sortedElements, true)) {
                $sortedElements[] = $element;
            }
        }

        return $sortedElements;
    }

    public function isEmpty(): bool
    {
        return $this->elements === [];
    }
}
