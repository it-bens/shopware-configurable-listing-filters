import template from './itb-configurable-listing-filters-form-range.html.twig';

const { mapPropertyErrors } = Shopware.Component.getComponentHelper();

export default Shopware.Component.wrapComponentConfig({
    template,

    props: {
        listingFilterConfiguration: {
            type: Object as () => EntitySchema.itb_lfc_range,
            required: false,
            default: null,
        },
    },

    computed: {
        ...mapPropertyErrors('listingFilterConfiguration', [
            'unit',
        ]),
    },
});
