import deDE from './module/snippet/de-DE.json';
import enGB from './module/snippet/en-GB.json';
import { location } from '@shopware-ag/admin-extension-sdk';

if (!location.isIframe()) {
    Shopware.Component.register('itb-configurable-listing-filters-form-basics', () => import('./component/itb-configurable-listing-filters-form-basics'));
    Shopware.Component.register('itb-configurable-listing-filters-form-dal-path-field', () => import('./component/itb-configurable-listing-filters-form-dal-path-field'));
    Shopware.Component.register('itb-configurable-listing-filters-form-multi-select', () => import('./component/itb-configurable-listing-filters-form-multi-select'));
    Shopware.Component.register('itb-configurable-listing-filters-form-range', () => import('./component/itb-configurable-listing-filters-form-range'));
    Shopware.Component.register('itb-configurable-listing-filters-form-range-interval', () => import('./component/itb-configurable-listing-filters-form-range-interval'));
    Shopware.Component.register('itb-configurable-listing-filters-form-range-interval-interval', () => import('./component/itb-configurable-listing-filters-form-range-interval-interval'));
    Shopware.Component.register('itb-configurable-listing-filters-presets-button-group', () => import('./component/itb-configurable-listing-filters-presets-button-group'));
    Shopware.Component.register('itb-configurable-listing-filters-edit', () => import('./page/itb-configurable-listing-filters-edit'));
    Shopware.Component.register('itb-configurable-listing-filters-list', () => import('./page/itb-configurable-listing-filters-list'));

    Shopware.Module.register('itb-configurable-listing-filters', {
        type: 'plugin',
        name: 'ConfigurableListingFilters',
        title: 'itb-configurable-listing-filters.general.moduleTitle',
        description: 'itb-configurable-listing-filters.general.moduleDescription',
        color: '#9AA8B5',
        icon: 'regular-filter',

        snippets: {
            'de-DE': deDE,
            'en-GB': enGB,
        },

        routePrefixName: 'itb.configurable-listing-filters',
        routePrefixPath: 'itb/configurable-listing-filters',

        routes: {
            list: {
                component: 'itb-configurable-listing-filters-list',
                path: 'list',
            },
            create: {
                component: 'itb-configurable-listing-filters-edit',
                path: 'create/:type',
                meta: {
                    parentPath: 'itb.configurable-listing-filters.list',
                },
            },
            edit: {
                component: 'itb-configurable-listing-filters-edit',
                path: 'edit/:type/:id',
                meta: {
                    parentPath: 'itb.configurable-listing-filters.list',
                },
            },
        },

        navigation: [{
            path: 'itb.configurable-listing-filters.list',
            id: 'itb-configurable-listing-filters',
            parent: 'sw-catalogue',
            position: 100,
            label: 'itb-configurable-listing-filters.general.moduleTitle',
        }],
    });
}
