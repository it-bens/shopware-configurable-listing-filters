import Data from './data.json';
import type { Entity } from '@shopware-ag/admin-extension-sdk/es/data/_internals/Entity';
import { data } from '@shopware-ag/admin-extension-sdk';

export async function getManufacturerListingFilterConfiguration(): Promise<Entity<'itb_lfc_multi_select'>> {
    const listingFilterConfigurationRepository = data.repository('itb_lfc_multi_select');
    const listingFilterConfiguration = await listingFilterConfigurationRepository.create(Shopware.Context.api);

    if (!listingFilterConfiguration) {
        throw new Error('Failed to create listing filter configuration');
    }

    listingFilterConfiguration.dalField = Data.dalField;
    listingFilterConfiguration.displayName = Data.displayName;
    listingFilterConfiguration.position = Data.position;
    listingFilterConfiguration.enabled = Data.enabled;
    listingFilterConfiguration.twigTemplate = Data.twigTemplate;
    listingFilterConfiguration.salesChannelId = Data.salesChannelId;
    listingFilterConfiguration.sortingOrder = Data.sortingOrder;
    listingFilterConfiguration.allowedElements = Data.allowedElements;
    listingFilterConfiguration.forbiddenElements = Data.forbiddenElements;
    listingFilterConfiguration.elementPrefix = Data.elementPrefix;
    listingFilterConfiguration.elementSuffix = Data.elementSuffix;
    listingFilterConfiguration.explicitElementSorting = Data.explicitElementSorting;

    return listingFilterConfiguration;
}
