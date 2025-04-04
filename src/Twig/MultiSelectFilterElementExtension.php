<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Twig;

use ITB\ITBConfigurableListingFilters\ListingFilter\MultiSelect\Storefront\Element as MultiSelectFilterElement;
use ReflectionClass;
use Twig\Extension\AbstractExtension;
use Twig\TwigTest;

final class MultiSelectFilterElementExtension extends AbstractExtension
{
    public function getTests(): array
    {
        return [
            'instanceof' => new TwigTest('multi_select_filter_element', $this->isMultiSelectFilterElement(...)),
        ];
    }

    public function isMultiSelectFilterElement(mixed $var): bool
    {
        if (is_object($var) === false) {
            return false;
        }

        return (new ReflectionClass(MultiSelectFilterElement::class))->isInstance($var);
    }
}
