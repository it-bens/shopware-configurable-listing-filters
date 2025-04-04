import deDE from './module/snippet/de-DE.json';
import enGB from './module/snippet/en-GB.json';

import './page/itb-configurable-listing-filters-list';
import './page/itb-configurable-listing-filters-create';
import './page/itb-configurable-listing-filters-detail';
import './component/itb-checkbox-filter-form';
import './component/itb-multi-select-filter-form';
import './component/itb-range-filter-form';

// Modul registrieren
Shopware.Module.register('itb-configurable-listing-filters', {
    type: 'plugin',
    name: 'ConfigurableListingFilters',
    title: 'itb-configurable-listing-filters.general.mainMenuItemGeneral',
    description: 'itb-configurable-listing-filters.general.descriptionTextModule',
    color: '#9AA8B5',
    icon: 'regular-filter',

    snippets: {
        'de-DE': deDE,
        'en-GB': enGB
    },

    routes: {
        list: {
            component: 'itb-configurable-listing-filters-list',
            path: 'list',
            meta: {
                parentPath: 'sw.catalogue.index',
                privilege: 'itb_configurable_listing_filters.viewer'
            }
        },
        create: {
            component: 'itb-configurable-listing-filters-create',
            path: 'create/:type',
            meta: {
                parentPath: 'itb.configurable.listing.filters.list',
                privilege: 'itb_configurable_listing_filters.creator'
            }
        },
        detail: {
            component: 'itb-configurable-listing-filters-detail',
            path: 'detail/:id/:filterType',
            meta: {
                parentPath: 'itb.configurable.listing.filters.list',
                privilege: 'itb_configurable_listing_filters.viewer'
            }
        }
    },

    navigation: [{
        id: 'itb-configurable-listing-filters',
        path: 'itb.configurable.listing.filters.list',
        parent: 'sw-catalogue',
        position: 100,
        label: 'itb-configurable-listing-filters.general.mainMenuItemGeneral',
        privilege: 'itb_configurable_listing_filters.viewer',
        beforeNavigate(): boolean {
            return true;
        }
    }]
});