import template from './itb-configurable-listing-filters-presets-button-group.html.twig';
import Repository from "src/core/data/repository.data";
import {getManufacturerListingFilterConfiguration} from '../../fixtures/manufacturer-listing-filter-configuration';
import {getPriceListingFilterConfiguration} from '../../fixtures/price-listing-filter-configuration';
import {getRatingListingFilterConfiguration} from '../../fixtures/rating-listing-filter-configuration';
import {getShippingFreeListingFilterConfiguration} from '../../fixtures/shipping-free-listing-filter-configuration';

const { Mixin } = Shopware;

// eslint-disable-next-line sw-deprecation-rules/private-feature-declarations
Shopware.Component.register('itb-configurable-listing-filters-presets-button-group', {
    template,

    inject: [
        'repositoryFactory',
    ],

    mixins: [
        Mixin.getByName('itbConfigurableListingFiltersLocator'),
    ],


    methods: {
        async createListingFilterConfigurationForManufacturer(): Promise<void> {
            const listingFilterConfigurationRepository: Repository<EntitySchema.itb_lfc_multi_select> = this.getRepositoryByEntityName('itb_lfc_multi_select', this.repositoryFactory);
            const listingFilterConfiguration = getManufacturerListingFilterConfiguration(this.repositoryFactory);
            await listingFilterConfigurationRepository.save(listingFilterConfiguration, Shopware.Context.api)

            this.$emit('itb-listing-filter-configuration-added');
        },

        async createListingFilterConfigurationForPrice(): Promise<void> {
            const listingFilterConfigurationRepository: Repository<EntitySchema.itb_lfc_range> = this.getRepositoryByEntityName('itb_lfc_range', this.repositoryFactory);
            const listingFilterConfiguration = getPriceListingFilterConfiguration(this.repositoryFactory);
            await listingFilterConfigurationRepository.save(listingFilterConfiguration, Shopware.Context.api)

            this.$emit('itb-listing-filter-configuration-added');
        },

        async createListingFilterConfigurationForRating(): Promise<void> {
            const listingFilterConfigurationRepository: Repository<EntitySchema.itb_lfc_range_interval> = this.getRepositoryByEntityName('itb_lfc_range_interval', this.repositoryFactory);
            const listingFilterConfiguration = getRatingListingFilterConfiguration(this.repositoryFactory);
            await listingFilterConfigurationRepository.save(listingFilterConfiguration, Shopware.Context.api)

            this.$emit('itb-listing-filter-configuration-added');
        },

        async createListingFilterConfigurationForShippingFree(): Promise<void> {
            const listingFilterConfigurationRepository: Repository<EntitySchema.itb_lfc_checkbox> = this.getRepositoryByEntityName('itb_lfc_checkbox', this.repositoryFactory);
            const listingFilterConfiguration = getShippingFreeListingFilterConfiguration(this.repositoryFactory);
            await listingFilterConfigurationRepository.save(listingFilterConfiguration, Shopware.Context.api)

            this.$emit('itb-listing-filter-configuration-added');
        }
    }
});
