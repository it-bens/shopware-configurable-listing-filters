import { data } from '@shopware-ag/admin-extension-sdk';
import template from './itb-configurable-listing-filters-form-basics.html.twig';

const { mapPropertyErrors } = Shopware.Component.getComponentHelper();

export default Shopware.Component.wrapComponentConfig({
    template,

    props: {
        filterType: {
            type: String,
            required: true,
        },
        listingFilterConfiguration: {
            type: Object as () => ItbConfigurableListingFilters.ListingFilterConfiguration,
            required: false,
            default: null,
        },
    },

    computed: {
        ...mapPropertyErrors('listingFilterConfiguration', [
            'dalField',
            'displayName',
            'twigTemplate',
        ]),

        salesChannelRepository() {
            return data.repository('sales_channel');
        },

        salesChannelCriteria() {
            const criteria = new data.Classes.Criteria();
            criteria.addSorting(data.Classes.Criteria.sort('name', 'ASC'));
            return criteria;
        },

        isDisplayNameRequired(): boolean {
            return Shopware.State.getters['context/isSystemDefaultLanguage'];
        },
    },

    async created() {
        await this.createdComponent();
    },

    methods: {
        async createdComponent(): Promise<void> {
        },
    },
});
