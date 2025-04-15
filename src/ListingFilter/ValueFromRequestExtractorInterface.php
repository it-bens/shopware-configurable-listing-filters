<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\ListingFilter;

use Symfony\Component\HttpFoundation\Request;

interface ValueFromRequestExtractorInterface
{
    public function extractValueFromRequest(Request $request, string $key): string;
}
