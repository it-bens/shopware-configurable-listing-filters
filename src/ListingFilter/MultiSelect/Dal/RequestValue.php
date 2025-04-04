<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\ListingFilter\MultiSelect\Dal;

final class RequestValue
{
    /**
     * @param array<float|int|string> $values
     */
    public function __construct(
        public readonly array $values,
    ) {
    }

    public function isFiltered(): bool
    {
        return $this->values !== [];
    }
}
