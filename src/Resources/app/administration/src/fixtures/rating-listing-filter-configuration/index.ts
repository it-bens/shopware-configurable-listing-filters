import {Entity} from "@shopware-ag/admin-extension-sdk/es/data/_internals/Entity";
import Data from './data.json'
import RepositoryFactory from "src/core/data/repository-factory.data";

export function getRatingListingFilterConfiguration(repositoryFactory: RepositoryFactory): Entity<EntitySchema.itb_lfc_range_interval> {
    const listingFilterConfigurationRepository = repositoryFactory.create('itb_lfc_range_interval');
    const listingFilterConfiguration = listingFilterConfigurationRepository.create(Shopware.Context.api);

    listingFilterConfiguration.dalField = Data['dalField'];
    listingFilterConfiguration.displayName = Data['displayName'];
    listingFilterConfiguration.position = Data['position'];
    listingFilterConfiguration.enabled = Data['enabled'];
    listingFilterConfiguration.twigTemplate = Data['twigTemplate'];
    listingFilterConfiguration.salesChannelId = Data['salesChannelId'];
    listingFilterConfiguration.elementPrefix = Data['elementPrefix'];
    listingFilterConfiguration.elementSuffix = Data['elementSuffix'];
    listingFilterConfiguration.intervals = [];

    const intervalRepository = repositoryFactory.create('itb_lfc_range_interval_interval');
    Data.intervals.forEach((interval => {
        const intervalEntity = intervalRepository.create(Shopware.Context.api);

        intervalEntity.min = interval.min;
        intervalEntity.max = interval.max;
        intervalEntity.title = interval.title;
        intervalEntity.position = interval.position;
        listingFilterConfiguration.intervals.push(intervalEntity);
    }));

    return listingFilterConfiguration;
}
