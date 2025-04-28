import type RepositoryFactory from "src/core/data/repository-factory.data";
import Criteria from "@shopware-ag/admin-extension-sdk/es/data/Criteria";

const { Component, Mixin } = Shopware;

export default Mixin.register('itbConfigurableListingFiltersLocator', Component.wrapComponentConfig({
    methods: {
        getRepositoryByFilterType: function (listingFilterConfigurationType: string, repositoryFactory: RepositoryFactory) {
            switch (listingFilterConfigurationType) {
                case 'checkbox':
                    return repositoryFactory.create('itb_listing_filter_configuration_checkbox');
                case 'multi-select':
                    return repositoryFactory.create('itb_listing_filter_configuration_multi_select');
                case 'range':
                    return repositoryFactory.create('itb_listing_filter_configuration_range');
                case 'range-interval':
                    return repositoryFactory.create('itb_listing_filter_configuration_range_interval');
                default:
                    throw new Error(`Invalid listing filter configuration type: ${listingFilterConfigurationType}`);
            }
        },

        getRepositoryByEntityName: function (listingFilterConfigurationType: string, repositoryFactory: RepositoryFactory) {
            switch (listingFilterConfigurationType) {
                case 'itb_listing_filter_configuration_checkbox':
                    return repositoryFactory.create('itb_listing_filter_configuration_checkbox');
                case 'itb_listing_filter_configuration_multi_select':
                    return repositoryFactory.create('itb_listing_filter_configuration_multi_select');
                case 'itb_listing_filter_configuration_range':
                    return repositoryFactory.create('itb_listing_filter_configuration_range');
                case 'itb_listing_filter_configuration_range_interval':
                    return repositoryFactory.create('itb_listing_filter_configuration_range_interval');
                default:
                    throw new Error(`Invalid entity name: ${listingFilterConfigurationType}`);
            }
        },

        getDefaultTwigTemplateByFilterType: function (listingFilterConfigurationType: string) {
            switch (listingFilterConfigurationType) {
                case 'checkbox':
                    return '@Storefront/storefront/component/listing/filter/filter-boolean.html.twig';
                case 'multi-select':
                    return '@Storefront/storefront/component/listing/filter/filter-multi-select.html.twig';
                case 'range':
                    return '@Storefront/storefront/component/listing/filter/filter-range.html.twig';
                case 'range-interval':
                    return '@Storefront/storefront/component/listing/filter/filter-multi-select.html.twig';
                default:
                    throw new Error(`Invalid listing filter configuration type: ${listingFilterConfigurationType}`);
            }
        },

        getTranslationKeyForCreatePageTitleByFilterType: function (listingFilterConfigurationType: string) {
            switch (listingFilterConfigurationType) {
                case 'checkbox':
                    return 'itb-configurable-listing-filters.form.checkbox.createPageTitle';
                case 'multi-select':
                    return 'itb-configurable-listing-filters.form.multiSelect.createPageTitle';
                case 'range':
                    return 'itb-configurable-listing-filters.form.range.createPageTitle';
                case 'range-interval':
                    return 'itb-configurable-listing-filters.form.rangeInterval.createPageTitle';
                default:
                    throw new Error(`Invalid listing filter configuration type: ${listingFilterConfigurationType}`);
            }
        },

        getFilterTypeByEntityName: function (entityName: string) {
            switch (entityName) {
                case 'itb_listing_filter_configuration_checkbox':
                    return 'checkbox';
                case 'itb_listing_filter_configuration_multi_select':
                    return 'multi-select';
                case 'itb_listing_filter_configuration_range':
                    return 'range';
                case 'itb_listing_filter_configuration_range_interval':
                    return 'range-interval';
                default:
                    throw new Error(`Invalid entity name: ${entityName}`);
            }
        },

        getCriteriaByFilterType: function (listingFilterConfigurationType: string) {
            const criteria = new Criteria();
            criteria.addAssociation('salesChannel');

            switch (listingFilterConfigurationType) {
                case 'checkbox':
                    return criteria;
                case 'multi-select':
                    return criteria;
                case 'range':
                    return criteria;
                case 'range-interval':
                    criteria.addAssociation('intervals');
                    return criteria;
                default:
                    throw new Error(`Invalid listing filter configuration type: ${listingFilterConfigurationType}`);
            }
        },

        getCriteriaByEntityName: function (listingFilterConfigurationType: string) {
            const criteria = new Criteria();
            criteria.addAssociation('salesChannel');

            switch (listingFilterConfigurationType) {
                case 'itb_listing_filter_configuration_checkbox':
                    return criteria;
                case 'itb_listing_filter_configuration_multi_select':
                    return criteria;
                case 'itb_listing_filter_configuration_range':
                    return criteria;
                case 'itb_listing_filter_configuration_range_interval':
                    criteria.addAssociation('intervals');
                    criteria.addAssociation('intervals.rangeIntervalListingFilterConfiguration');
                    return criteria;
                default:
                    throw new Error(`Invalid entity name: ${listingFilterConfigurationType}`);
            }
        }
    }
}));