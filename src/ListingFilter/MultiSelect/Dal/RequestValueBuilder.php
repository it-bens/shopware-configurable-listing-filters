<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\ListingFilter\MultiSelect\Dal;

use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\MultiSelect\MultiSelectListingFilterConfigurationEntity;
use ITB\ITBConfigurableListingFilters\ListingFilter\MultiSelectValueSplitterInterface;
use ITB\ITBConfigurableListingFilters\ListingFilter\ValueFromRequestExtractorInterface;
use Symfony\Component\HttpFoundation\Request;

final class RequestValueBuilder implements RequestValueBuilderInterface
{
    /**
     * @codeCoverageIgnore
     */
    public function __construct(
        private readonly ValueFromRequestExtractorInterface $valueFromRequestExtractor,
        private readonly MultiSelectValueSplitterInterface $multiSelectValueSplitter,
    ) {
    }

    public function buildRequestValue(MultiSelectListingFilterConfigurationEntity $configurationEntity, Request $request): RequestValue
    {
        $valuesAsString = $this->valueFromRequestExtractor->extractValueFromRequest($request, $configurationEntity->getFilterName());
        $values = $this->multiSelectValueSplitter->splitMultiSelectValue($valuesAsString, $configurationEntity);

        return new RequestValue($values);
    }
}
