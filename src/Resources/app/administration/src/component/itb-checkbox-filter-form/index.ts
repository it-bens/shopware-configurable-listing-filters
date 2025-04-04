import template from './itb-checkbox-filter-form.html.twig';

const { Component, Mixin } = Shopware;
const { Criteria } = Shopware.Data;
const { mapPropertyErrors } = Component.getComponentHelper();

// Erstellen der Komponente
const checkboxFilterForm = Component.register('itb-checkbox-filter-form', {
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
        filter: ITB.CheckboxFilter | null;
        salesChannels: Array<ITB.SalesChannel>;
        errors: ITB.FilterFormErrors;
    } {
        return {
            isLoading: false,
            isSaveSuccessful: false,
            filter: null,
            salesChannels: [],
            errors: {}
        };
    },

    computed: {
        filterRepository() {
            return this.repositoryFactory.create('itb_listing_filter_configuration_checkbox');
        },

        salesChannelRepository() {
            return this.repositoryFactory.create('sales_channel');
        },

        salesChannelCriteria() {
            const criteria = new Criteria();
            criteria.addSorting(Criteria.sort('name', 'ASC'));
            return criteria;
        },

        ...mapPropertyErrors(
            'filter',
            ['dalField', 'displayName', 'twigTemplate']
        ),

        filterDalFieldError(): ITB.FilterFormError | undefined {
            return this.errors.dalField;
        },

        filterDisplayNameError(): ITB.FilterFormError | undefined {
            return this.errors.displayName;
        },

        filterTwigTemplateError(): ITB.FilterFormError | undefined {
            return this.errors.twigTemplate;
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
            this.filter.twigTemplate = '@Storefront/storefront/component/listing/filter/filter-boolean.html.twig';
            this.isLoading = false;
        },

        loadExistingFilter(): Promise<ITB.CheckboxFilter> {
            return this.filterRepository.get(this.filterId).then(filter => {
                this.filter = filter;
                this.isLoading = false;
                return filter;
            });
        },

        loadFilterWithLanguage(languageId: string): Promise<ITB.CheckboxFilter | null> {
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

        save(): Promise<ITB.CheckboxFilter | void> {
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
            
            if (Object.keys(this.errors).length > 0) {
                this.isLoading = false;
                return Promise.reject(new Error('Validation failed'));
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

export default checkboxFilterForm;