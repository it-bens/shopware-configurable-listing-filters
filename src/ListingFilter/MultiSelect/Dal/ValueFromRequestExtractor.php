<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\ListingFilter\MultiSelect\Dal;

use Symfony\Component\HttpFoundation\Request;

final class ValueFromRequestExtractor implements ValueFromRequestExtractorInterface
{
    public function extractValueFromRequest(Request $request, string $key): string
    {
        if ($request->isMethod(Request::METHOD_POST)) {
            return (string) $request->request->get($key, '');
        }

        return (string) $request->query->get($key, '');
    }
}
