import {Entity} from "@shopware-ag/admin-extension-sdk/es/data/_internals/Entity";
import Data from './data.json'
import RepositoryFactory from "src/core/data/repository-factory.data";

export function getShippingFreeListingFilterConfiguration(repositoryFactory: RepositoryFactory): Entity<EntitySchema.itb_lfc_checkbox> {
    const listingFilterConfigurationRepository = repositoryFactory.create('itb_lfc_checkbox');
    const listingFilterConfiguration = listingFilterConfigurationRepository.create(Shopware.Context.api);

    listingFilterConfiguration.dalField = Data['dalField'];
    listingFilterConfiguration.displayName = Data['displayName'];
    listingFilterConfiguration.position = Data['position'];
    listingFilterConfiguration.enabled = Data['enabled'];
    listingFilterConfiguration.twigTemplate = Data['twigTemplate'];
    listingFilterConfiguration.salesChannelId = Data['salesChannelId'];

    return listingFilterConfiguration;
}
