<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\ListingFilter\RangeInterval;

use Shopware\Core\Content\Product\ProductDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Dbal\EntityDefinitionQueryHelper;
use Shopware\Core\Framework\DataAbstractionLayer\DefinitionInstanceRegistry;
use Shopware\Core\Framework\DataAbstractionLayer\Field\FloatField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IntField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\PriceField;

final class RangeAggregationCompatibilityChecker implements RangeAggregationCompatibilityCheckerInterface
{
    public function __construct(
        private readonly EntityDefinitionQueryHelper $queryHelper,
        private readonly DefinitionInstanceRegistry $definitionRegistry,
    ) {
    }

    public function isDalFieldRangeAggregationCompatible(string $dalField): bool
    {
        // The range aggregation parsing for DBAL requires a field type of PriceField, FloatField or IntField.
        $definition = $this->definitionRegistry->getByEntityName(ProductDefinition::ENTITY_NAME);
        $field = $this->queryHelper->getField($dalField, $definition, ProductDefinition::ENTITY_NAME);

        return $field instanceof PriceField || $field instanceof FloatField || $field instanceof IntField;
    }
}
