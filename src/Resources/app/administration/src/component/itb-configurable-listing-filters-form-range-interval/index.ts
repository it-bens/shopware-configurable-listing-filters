import template from './itb-configurable-listing-filters-form-range-interval.html.twig';

const { mapPropertyErrors } = Shopware.Component.getComponentHelper();

// eslint-disable-next-line sw-deprecation-rules/private-feature-declarations
Shopware.Component.register('itb-configurable-listing-filters-form-range-interval', {
    template,

    inject: [
        'repositoryFactory',
    ],

    props: {
        listingFilterConfiguration: {
            type: Object as () => EntitySchema.itb_lfc_range_interval,
            required: false,
            default: null
        }
    },

    computed: {
        ...mapPropertyErrors('listingFilterConfiguration', [
            'elementPrefix',
            'elementSuffix',
        ]),
    },
});
