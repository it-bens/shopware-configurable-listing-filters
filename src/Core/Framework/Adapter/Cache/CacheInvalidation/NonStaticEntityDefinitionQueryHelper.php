<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Core\Framework\Adapter\Cache\CacheInvalidation;

use Shopware\Core\Framework\DataAbstractionLayer\Dbal\EntityDefinitionQueryHelper;
use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;

/**
 * This class is a wrapper around the EntityDefinitionQueryHelper to provide a non-static access to used static methods.
 * Static methods cannot be mocked in tests.
 *
 * @codeCoverageIgnore
 */
final class NonStaticEntityDefinitionQueryHelper implements NonStaticEntityDefinitionQueryHelperInterface
{
    public function __construct(
        private readonly EntityDefinitionQueryHelper $queryHelper,
    ) {
    }

    public function getAssociatedDefinition(EntityDefinition $rootDefinition, string $field): EntityDefinition
    {
        return $this->queryHelper->getAssociatedDefinition($rootDefinition, $field);
    }
}
