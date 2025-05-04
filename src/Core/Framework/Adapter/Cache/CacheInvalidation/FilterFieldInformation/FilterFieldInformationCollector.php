<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Core\Framework\Adapter\Cache\CacheInvalidation\FilterFieldInformation;

use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\ListingFilterConfigurationCollection;
use ITB\ITBConfigurableListingFilters\Core\Framework\Adapter\Cache\CacheInvalidation\NonStaticEntityDefinitionQueryHelperInterface;
use Shopware\Core\Content\Product\ProductDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Dbal\EntityDefinitionQueryHelper;
use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Field;
use Shopware\Core\Framework\DataAbstractionLayer\Field\TranslatedField;

final class FilterFieldInformationCollector implements FilterFieldInformationCollectorInterface
{
    public function __construct(
        private readonly ProductDefinition $productDefinition,
        private readonly NonStaticEntityDefinitionQueryHelperInterface $nonStaticQueryHelper,
        private readonly EntityDefinitionQueryHelper $queryHelper,
    ) {
    }

    public function collect(ListingFilterConfigurationCollection $listingFilterConfigurationCollection): FilterFieldInformationCollection
    {
        $filterFieldInformationCollection = new FilterFieldInformationCollection();
        foreach ($listingFilterConfigurationCollection as $listingFilterConfiguration) {
            $definition = $this->nonStaticQueryHelper->getAssociatedDefinition(
                $this->productDefinition,
                $listingFilterConfiguration->getFullyQualifiedDalField()
            );
            $field = $this->queryHelper->getField(
                $listingFilterConfiguration->getFullyQualifiedDalField(),
                $this->productDefinition,
                'product'
            );

            if (! $field instanceof Field) {
                throw new \RuntimeException(sprintf(
                    'Field "%s" not found in product definition',
                    $listingFilterConfiguration->getFullyQualifiedDalField()
                ));
            }

            if ($field instanceof TranslatedField) {
                $definition = $definition->getTranslationDefinition();
                if (! $definition instanceof EntityDefinition) {
                    throw new \RuntimeException(sprintf(
                        'Translation definition not found for field "%s"',
                        $listingFilterConfiguration->getFullyQualifiedDalField()
                    ));
                }
            }

            $filterFieldInformation = new FilterFieldInformation(
                $listingFilterConfiguration->getFullyQualifiedDalField(),
                $definition,
                $field
            );
            $filterFieldInformationCollection->add($filterFieldInformation);
        }

        return $filterFieldInformationCollection;
    }
}
