<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Core\Framework\Adapter\Cache\CacheInvalidation;

use Shopware\Core\Framework\DataAbstractionLayer\Dbal\EntityDefinitionQueryHelper;
use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\AssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToManyAssociationField;

/**
 * This class used to be a wrapper around the EntityDefinitionQueryHelper to provide a non-static access to used static methods.
 * Static methods cannot be mocked in tests.
 * It is now used as a backport for the EntityDefinitionQueryHelper::getAssociatedDefinition method.
 *
 * @codeCoverageIgnore
 */
final class NonStaticEntityDefinitionQueryHelper implements NonStaticEntityDefinitionQueryHelperInterface
{
    /**
    public function __construct(
        private readonly EntityDefinitionQueryHelper $queryHelper,
    ) {
    }**/

    /**
     * The method was introduced with SW 6.5.8.16.
     * It is copied here to be used in versions like 6.5.7.4 or lower 6.5.8 versions.
     */
    public function getAssociatedDefinition(EntityDefinition $rootDefinition, string $field): EntityDefinition
    {
        // The call be safely used in SW 6.7. SW 6.6 introduced the method in SW 6.6.5.0
        // return $this->queryHelper->getAssociatedDefinition($rootDefinition, $field);

        $fields = EntityDefinitionQueryHelper::getFieldsOfAccessor($rootDefinition, $field, false);

        array_pop($fields);
        $field = array_pop($fields);

        if ($field === null) {
            return $rootDefinition;
        }

        if ($field instanceof ManyToManyAssociationField) {
            return $field->getToManyReferenceDefinition();
        }

        if ($field instanceof AssociationField) {
            return $field->getReferenceDefinition();
        }

        return $rootDefinition;
    }
}
