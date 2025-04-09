<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\ListingFilter\Checkbox\Dal;

final class RequestValue
{
    /**
     * @codeCoverageIgnore
     */
    public function __construct(
        public readonly bool $value,
    ) {
    }

    public function isFiltered(): bool
    {
        return $this->value;
    }
}
