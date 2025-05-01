import Data from './data.json';
import type { Entity } from '@shopware-ag/admin-extension-sdk/es/data/_internals/Entity';
import { data } from '@shopware-ag/admin-extension-sdk';

export async function getShippingFreeListingFilterConfiguration(): Promise<Entity<'itb_lfc_checkbox'>> {
    const listingFilterConfigurationRepository = data.repository('itb_lfc_checkbox');
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

    return listingFilterConfiguration;
}
