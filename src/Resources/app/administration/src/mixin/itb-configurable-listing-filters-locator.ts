import { defineComponent } from 'vue';
import type RepositoryFactory from "src/core/data/repository-factory.data";

const { Mixin } = Shopware;

export default Mixin.register('itbConfigurableListingFiltersLocator', defineComponent({
    methods: {
        getRepositoryByFilterType: function (listingFilterConfigurationType: string, repositoryFactory: RepositoryFactory) {
            switch (listingFilterConfigurationType) {
                case 'checkbox':
                    return repositoryFactory.create('itb_listing_filter_configuration_checkbox');
                case 'multi-select':
                    return repositoryFactory.create('itb_listing_filter_configuration_multi_select');
                case 'range':
                    return repositoryFactory.create('itb_listing_filter_configuration_range');
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
                default:
                    throw new Error(`Invalid listing filter configuration type: ${listingFilterConfigurationType}`);
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
                default:
                    throw new Error(`Invalid entity name: ${entityName}`);
            }
        }
    }
}));