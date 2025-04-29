import {Entity} from "@shopware-ag/admin-extension-sdk/es/data/_internals/Entity";
import Data from './data.json'
import RepositoryFactory from "src/core/data/repository-factory.data";

export function getManufacturerListingFilterConfiguration(repositoryFacctor: RepositoryFactory): Entity<EntitySchema.itb_lfc_multi_select> {
    const listingFilterConfigurationRepository = repositoryFacctor.create('itb_lfc_multi_select');
    const listingFilterConfiguration = listingFilterConfigurationRepository.create(Shopware.Context.api);

    listingFilterConfiguration.dalField = Data['dalField'];
    listingFilterConfiguration.displayName = Data['displayName'];
    listingFilterConfiguration.position = Data['position'];
    listingFilterConfiguration.enabled = Data['enabled'];
    listingFilterConfiguration.twigTemplate = Data['twigTemplate'];
    listingFilterConfiguration.salesChannelId = Data['salesChannelId'];
    listingFilterConfiguration.sortingOrder = Data['sortingOrder'];
    listingFilterConfiguration.allowedElements = Data['allowedElements'];
    listingFilterConfiguration.forbiddenElements = Data['forbiddenElements'];
    listingFilterConfiguration.elementPrefix = Data['elementPrefix'];
    listingFilterConfiguration.elementSuffix = Data['elementSuffix'];
    listingFilterConfiguration.explicitElementSorting = Data['explicitElementSorting'];

    return listingFilterConfiguration;
}
