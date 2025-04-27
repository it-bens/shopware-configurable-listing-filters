import type ItbConfigurableListingFiltersLocator from "../mixin/itb-configurable-listing-filters-locator";

declare module 'vue/types/vue' {
    interface MixinContainer {
        'itbConfigurableListingFiltersLocator': typeof ItbConfigurableListingFiltersLocator;
    }
}