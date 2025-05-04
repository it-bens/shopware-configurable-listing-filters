import template from './itb-configurable-listing-filters-form-range-interval.html.twig';

const { mapPropertyErrors } = Shopware.Component.getComponentHelper();

export default Shopware.Component.wrapComponentConfig({
    template,

    props: {
        listingFilterConfiguration: {
            type: Object as () => EntitySchema.itb_lfc_range_interval,
            required: false,
            default: null,
        },
    },

    computed: {
        ...mapPropertyErrors('listingFilterConfiguration', [
            'elementPrefix',
            'elementSuffix',
        ]),
    },
});
