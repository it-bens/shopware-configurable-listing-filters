import template from './itb-configurable-listing-filters-form-multi-select.html.twig';

const { mapPropertyErrors } = Shopware.Component.getComponentHelper();

interface SortingOption {
    id: string;
    name: string;
}

Shopware.Component.register('itb-configurable-listing-filters-form-multi-select', {
    template,

    props: {
        listingFilterConfiguration: {
            type: Object as () => EntitySchema.itb_lfc_multi_select,
            required: false,
            default: null,
        },
    },

    data(): {
        listingFilterConfigurationAllowedElements: string;
        listingFilterConfigurationForbiddenElements: string;
        listingFilterConfigurationExplicitElementSorting: string;
        sortingOptions: Array<SortingOption>;
    } {
        return {
            listingFilterConfigurationAllowedElements: (this.listingFilterConfiguration.allowedElements || []).join('\n'),
            listingFilterConfigurationForbiddenElements: (this.listingFilterConfiguration.forbiddenElements || []).join('\n'),
            listingFilterConfigurationExplicitElementSorting: (this.listingFilterConfiguration.explicitElementSorting || []).join('\n'),
            sortingOptions: [
                { id: 'asc', name: this.$tc('itb-configurable-listing-filters.form.multiSelect.sortingOrderAsc') },
                { id: 'desc', name: this.$tc('itb-configurable-listing-filters.form.multiSelect.sortingOrderDesc') },
            ],
        };
    },

    computed: {
        ...mapPropertyErrors('listingFilterConfiguration', [
            'sortingOrder',
            'allowedElements',
            'forbiddenElements',
            'elementPrefix',
            'elementSuffix',
            'explicitElementSorting',
        ]),
    },

    methods: {
        stringToArray(value: string): string[] {
            return value.split('\n').map(item => item.trim()).filter(item => item !== '');
        },

        updateListingFilterConfigurationAllowedElements() {
            this.listingFilterConfiguration.allowedElements = this.stringToArray(this.listingFilterConfigurationAllowedElements);
        },

        updateListingFilterConfigurationForbiddenElements() {
            this.listingFilterConfiguration.forbiddenElements = this.stringToArray(this.listingFilterConfigurationForbiddenElements);
        },

        updateListingFilterConfigurationExplicitElementSorting() {
            this.listingFilterConfiguration.explicitElementSorting = this.stringToArray(this.listingFilterConfigurationExplicitElementSorting);
        },
    },
});
