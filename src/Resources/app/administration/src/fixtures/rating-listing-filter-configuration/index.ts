import Data from './data.json';
import type { Entity } from '@shopware-ag/admin-extension-sdk/es/data/_internals/Entity';
import { data } from '@shopware-ag/admin-extension-sdk';

export async function getRatingListingFilterConfiguration(): Promise<Entity<'itb_lfc_range_interval'>> {
    const listingFilterConfigurationRepository = data.repository('itb_lfc_range_interval');
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
    listingFilterConfiguration.elementPrefix = Data.elementPrefix;
    listingFilterConfiguration.elementSuffix = Data.elementSuffix;
    listingFilterConfiguration.intervals = [];

    const intervalRepository = data.repository('itb_lfc_range_interval_interval');
    Data.intervals.forEach((async interval => {
        const intervalEntity = await intervalRepository.create(Shopware.Context.api);

        if (!intervalEntity) {
            throw new Error('Failed to create interval entity');
        }

        intervalEntity.min = interval.min;
        intervalEntity.max = interval.max;
        intervalEntity.title = interval.title;
        intervalEntity.position = interval.position;
        listingFilterConfiguration.intervals.push(intervalEntity);
    }));

    return listingFilterConfiguration;
}
