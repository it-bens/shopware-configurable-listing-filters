import { data } from '@shopware-ag/admin-extension-sdk';
import { getManufacturerListingFilterConfiguration } from '../../../src/fixtures/manufacturer-listing-filter-configuration';
import { getPriceListingFilterConfiguration } from '../../../src/fixtures/price-listing-filter-configuration';
import { getRatingListingFilterConfiguration } from '../../../src/fixtures/rating-listing-filter-configuration';
import { getShippingFreeListingFilterConfiguration } from '../../../src/fixtures/shipping-free-listing-filter-configuration';
import template from './itb-configurable-listing-filters-presets-button-group.html.twig';

export default Shopware.Component.wrapComponentConfig({
    template,

    methods: {
        async createListingFilterConfigurationForManufacturer(): Promise<void> {
            const listingFilterConfigurationRepository = data.repository('itb_lfc_multi_select');
            const listingFilterConfiguration = await getManufacturerListingFilterConfiguration();
            await listingFilterConfigurationRepository.save(listingFilterConfiguration, Shopware.Context.api);

            this.$emit('itb-listing-filter-configuration-added');
        },

        async createListingFilterConfigurationForPrice(): Promise<void> {
            const listingFilterConfigurationRepository = data.repository('itb_lfc_range');
            const listingFilterConfiguration = await getPriceListingFilterConfiguration();
            await listingFilterConfigurationRepository.save(listingFilterConfiguration, Shopware.Context.api);

            this.$emit('itb-listing-filter-configuration-added');
        },

        async createListingFilterConfigurationForRating(): Promise<void> {
            const listingFilterConfigurationRepository = data.repository('itb_lfc_range_interval');
            const listingFilterConfiguration = await getRatingListingFilterConfiguration();
            await listingFilterConfigurationRepository.save(listingFilterConfiguration, Shopware.Context.api);

            this.$emit('itb-listing-filter-configuration-added');
        },

        async createListingFilterConfigurationForShippingFree(): Promise<void> {
            const listingFilterConfigurationRepository = data.repository('itb_lfc_checkbox');
            const listingFilterConfiguration = await getShippingFreeListingFilterConfiguration();
            await listingFilterConfigurationRepository.save(listingFilterConfiguration, Shopware.Context.api);

            this.$emit('itb-listing-filter-configuration-added');
        },
    },
});
