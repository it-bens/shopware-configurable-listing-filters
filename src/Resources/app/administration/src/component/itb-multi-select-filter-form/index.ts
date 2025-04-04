import template from './itb-multi-select-filter-form.html.twig';

const { Component, Mixin } = Shopware;
const { Criteria } = Shopware.Data;
const { mapPropertyErrors } = Component.getComponentHelper();

interface SortingOption {
    value: string;
    label: string;
}

// Erstellen der Komponente
const multiSelectFilterForm = Component.register('itb-multi-select-filter-form', {
    template,

    inject: [
        'repositoryFactory'
    ],

    mixins: [
        Mixin.getByName('notification')
    ],

    props: {
        filterId: {
            type: String,
            required: false,
            default: null
        }
    },

    data(): {
        isLoading: boolean;
        isSaveSuccessful: boolean;
        filter: ITB.MultiSelectFilter | null;
        salesChannels: Array<ITB.SalesChannel>;
        errors: ITB.FilterFormErrors;
        sortingOptions: Array<SortingOption>;
    } {
        return {
            isLoading: false,
            isSaveSuccessful: false,
            filter: null,
            salesChannels: [],
            errors: {},
            sortingOptions: [
                { value: 'asc', label: this.$tc('itb-configurable-listing-filters.multiSelect.sortingOrderAsc') },
                { value: 'desc', label: this.$tc('itb-configurable-listing-filters.multiSelect.sortingOrderDesc') }
            ]
        };
    },

    computed: {
        filterRepository() {
            return this.repositoryFactory.create('itb_listing_filter_configuration_multi_select');
        },

        salesChannelRepository() {
            return this.repositoryFactory.create('sales_channel');
        },

        salesChannelCriteria() {
            const criteria = new Criteria();
            criteria.addSorting(Criteria.sort('name', 'ASC'));
            return criteria;
        },

        filterDalFieldError(): ITB.FilterFormError | undefined {
            return this.errors.dalField;
        },

        filterDisplayNameError(): ITB.FilterFormError | undefined {
            return this.errors.displayName;
        },

        filterTwigTemplateError(): ITB.FilterFormError | undefined {
            return this.errors.twigTemplate;
        },

        filterSortingOrderError(): ITB.FilterFormError | undefined {
            return this.errors.sortingOrder;
        }
    },

    created() {
        this.createdComponent();
    },

    methods: {
        createdComponent(): void {
            this.isLoading = true;
            this.loadSalesChannels();
            
            if (this.filterId) {
                this.loadExistingFilter();
            } else {
                this.createNewFilter();
            }
        },

        loadSalesChannels(): Promise<Array<ITB.SalesChannel>> {
            return this.salesChannelRepository.search(this.salesChannelCriteria).then(result => {
                this.salesChannels = result;
                return result;
            });
        },

        createNewFilter(): void {
            this.filter = this.filterRepository.create();
            this.filter.enabled = true;
            this.filter.twigTemplate = '@Storefront/storefront/component/listing/filter/filter-multi-select.html.twig';
            this.filter.sortingOrder = 'asc';
            
            // Initialize translatable fields
            this.filter.allowedElements = [];
            this.filter.forbiddenElements = [];
            this.filter.elementPrefix = '';
            this.filter.elementSuffix = '';
            this.filter.explicitElementSorting = [];
            
            this.isLoading = false;
        },

        loadExistingFilter(): Promise<ITB.MultiSelectFilter> {
            return this.filterRepository.get(this.filterId).then(filter => {
                this.filter = filter;
                this.isLoading = false;
                return filter;
            });
        },

        loadFilterWithLanguage(languageId: string): Promise<ITB.MultiSelectFilter | null> {
            this.isLoading = true;
            
            if (!this.filterId) {
                this.isLoading = false;
                return Promise.resolve(this.filter);
            }
            
            const context = { ...Shopware.Context.api, languageId };
            
            return this.filterRepository.get(this.filterId, context).then(filter => {
                this.filter = filter;
                this.isLoading = false;
                return filter;
            }).catch(() => {
                this.isLoading = false;
                return Promise.resolve(this.filter);
            });
        },

        arrayToString(array: string[] | null | undefined): string {
            if (!array || !Array.isArray(array)) {
                return '';
            }
            
            return array.join(', ');
        },
        
        stringToArray(string: string | null | undefined): string[] {
            if (!string || typeof string !== 'string') {
                return [];
            }
            
            return string.split(',').map(item => item.trim()).filter(item => item !== '');
        },
        
        save(): Promise<ITB.MultiSelectFilter | void> {
            this.isLoading = true;

            // Manuelle Validierung
            this.errors = {};
            if (!this.filter?.dalField) {
                this.errors.dalField = { code: 'REQUIRED', detail: 'This field is required' };
            }
            if (!this.filter?.displayName) {
                this.errors.displayName = { code: 'REQUIRED', detail: 'This field is required' };
            }
            if (!this.filter?.twigTemplate) {
                this.errors.twigTemplate = { code: 'REQUIRED', detail: 'This field is required' };
            }
            if (!this.filter?.sortingOrder) {
                this.errors.sortingOrder = { code: 'REQUIRED', detail: 'This field is required' };
            }
            
            if (Object.keys(this.errors).length > 0) {
                this.isLoading = false;
                return Promise.reject(new Error('Validation failed'));
            }
            
            // Convert string inputs to arrays for storage
            if (this.filter && typeof this.filter.allowedElements === 'string') {
                this.filter.allowedElements = this.stringToArray(this.filter.allowedElements);
            }
            
            if (this.filter && typeof this.filter.forbiddenElements === 'string') {
                this.filter.forbiddenElements = this.stringToArray(this.filter.forbiddenElements);
            }
            
            if (this.filter && typeof this.filter.explicitElementSorting === 'string') {
                this.filter.explicitElementSorting = this.stringToArray(this.filter.explicitElementSorting);
            }

            return this.filterRepository.save(this.filter, Shopware.Context.api).then(() => {
                this.isLoading = false;
                this.isSaveSuccessful = true;
                
                this.$emit('saved');
                
                return Promise.resolve(this.filter!);
            }).catch((error) => {
                this.isLoading = false;
                
                this.createNotificationError({
                    title: this.$tc('itb-configurable-listing-filters.general.errorTitle'),
                    message: this.$tc('itb-configurable-listing-filters.general.saveErrorMessage')
                });
                
                return Promise.reject(error);
            });
        }
    }
});

export default multiSelectFilterForm;