import template from './itb-configurable-listing-filters-form-basics.html.twig';

const { Criteria } = Shopware.Data;
const { mapPropertyErrors } = Shopware.Component.getComponentHelper();

// eslint-disable-next-line sw-deprecation-rules/private-feature-declarations
Shopware.Component.register('itb-configurable-listing-filters-form-basics', {
    template,

    inject: [
        'repositoryFactory'
    ],

    props: {
        listingFilterConfiguration: {
            type: Object as () => ItbConfigurableListingFilters.ListingFilterConfiguration,
            required: false,
            default: null
        }
    },

    computed: {
        ...mapPropertyErrors('listingFilterConfiguration', [
            'dalField',
            'displayName',
            'twigTemplate'
        ]),

        salesChannelRepository() {
            return this.repositoryFactory.create('sales_channel');
        },

        salesChannelCriteria() {
            const criteria = new Criteria();
            criteria.addSorting(Criteria.sort('name', 'ASC'));
            return criteria;
        },

        isDisplayNameRequired() {
            return Shopware.State.getters['context/isSystemDefaultLanguage'];
        },
    },

    async created() {
        await this.createdComponent();
    },

    methods: {
        async createdComponent(): Promise<void> {
        },
    }
});
