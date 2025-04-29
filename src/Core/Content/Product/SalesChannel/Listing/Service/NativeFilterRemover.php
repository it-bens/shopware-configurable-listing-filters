<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Core\Content\Product\SalesChannel\Listing\Service;

use ITB\ITBConfigurableListingFilters\ITBConfigurableListingFilters;
use Shopware\Core\Content\Product\SalesChannel\Listing\FilterCollection;
use Shopware\Core\System\SystemConfig\SystemConfigService;

final class NativeFilterRemover implements NativeFilterRemoverInterface
{
    private const CONFIG_KEY_FILTER_NAME_MAP = [
        'enableManufacturerFilter' => 'manufacturer',
        'enablePropertiesFilter' => 'properties',
        'enablePriceFilter' => 'price',
        'enableRatingFilter' => 'rating',
        'enableShippingFreeFilter' => 'shipping-free',
    ];

    public function __construct(
        private readonly SystemConfigService $systemConfigService,
    ) {
    }

    public function removeNativeFilters(FilterCollection $filterCollection, string $salesChannelId): void
    {
        foreach (self::CONFIG_KEY_FILTER_NAME_MAP as $configKey => $filterName) {
            if ($this->systemConfigService->getBool($this->buildFullConfigKey($configKey), $salesChannelId) === false) {
                $filterCollection->remove($filterName);
            }
        }
    }

    private function buildFullConfigKey(string $configKey): string
    {
        return ITBConfigurableListingFilters::PLUGIN_NAME . '.config.' . $configKey;
    }
}
