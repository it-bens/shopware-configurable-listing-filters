import type ItbConfigurableListingFiltersLocator from "../mixin/itb-configurable-listing-filters-locator";

declare global {
    interface MixinContainer {
        'itbConfigurableListingFiltersLocator': typeof ItbConfigurableListingFiltersLocator;
    }
}