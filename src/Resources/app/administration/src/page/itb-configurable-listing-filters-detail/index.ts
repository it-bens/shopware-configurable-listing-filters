import template from './itb-configurable-listing-filters-detail.html.twig';

const { Component, Mixin } = Shopware;
const { Criteria } = Shopware.Data;

interface DetailPageData {
    isLoading: boolean;
    filterId: string | null;
    filterType: string | null;
    filterEntity: ITB.BaseFilter | null;
}

interface FilterFormComponent extends Vue {
    save(): Promise<ITB.BaseFilter | void>;
    loadFilterWithLanguage(languageId: string): Promise<ITB.BaseFilter | null>;
}

// Detail page component
Component.register('itb-configurable-listing-filters-detail', {
    template,

    inject: [
        'repositoryFactory',
        'acl'
    ],
    
    mixins: [
        Mixin.getByName('notification'),
        Mixin.getByName('placeholder')
    ],

    data(): DetailPageData {
        return {
            isLoading: false,
            filterId: null,
            filterType: null,
            filterEntity: null
        };
    },
    
    metaInfo() {
        return {
            title: this.pageTitle
        };
    },
    
    computed: {
        pageTitle(): string {
            if (this.filterEntity && this.filterEntity.displayName) {
                return this.filterEntity.displayName;
            }
            
            switch (this.filterType) {
                case 'checkbox':
                    return this.$tc('itb-configurable-listing-filters.detail.titleCheckbox');
                case 'multiSelect':
                    return this.$tc('itb-configurable-listing-filters.detail.titleMultiSelect');
                case 'range':
                    return this.$tc('itb-configurable-listing-filters.detail.titleRange');
                default:
                    return this.$tc('itb-configurable-listing-filters.detail.titleDefault');
            }
        },
        
        filterRepository() {
            switch (this.filterType) {
                case 'checkbox':
                    return this.repositoryFactory.create('itb_listing_filter_configuration_checkbox');
                case 'multiSelect':
                    return this.repositoryFactory.create('itb_listing_filter_configuration_multi_select');
                case 'range':
                    return this.repositoryFactory.create('itb_listing_filter_configuration_range');
                default:
                    return null;
            }
        }
    },

    created() {
        this.createdComponent();
    },

    methods: {
        createdComponent(): void {
            this.filterId = this.$route.params.id;
            this.filterType = this.$route.params.filterType;
            
            if (!['checkbox', 'multiSelect', 'range'].includes(this.filterType ?? '')) {
                this.createNotificationError({
                    title: this.$tc('itb-configurable-listing-filters.general.errorTitle'),
                    message: this.$tc('itb-configurable-listing-filters.general.invalidFilterType')
                });
                
                this.$router.push({ name: 'itb.configurable.listing.filters.list' });
                return;
            }
            
            this.loadFilterEntity();
        },
        
        loadFilterEntity(): void {
            this.isLoading = true;
            
            if (this.filterRepository && this.filterId) {
                this.filterRepository.get(this.filterId).then((entity) => {
                    this.filterEntity = entity;
                    this.isLoading = false;
                }).catch(() => {
                    this.createNotificationError({
                        title: this.$tc('itb-configurable-listing-filters.general.errorTitle'),
                        message: this.$tc('itb-configurable-listing-filters.general.loadingErrorMessage')
                    });
                    
                    this.isLoading = false;
                    this.$router.push({ name: 'itb.configurable.listing.filters.list' });
                });
            }
        },

        onSave(): void {
            const filterForm = this.$refs.filterForm as FilterFormComponent;
            if (filterForm) {
                filterForm.save();
            }
        },
        
        onCancel(): void {
            this.$router.push({ name: 'itb.configurable.listing.filters.list' });
        },
        
        onFilterSaved(): void {
            this.createNotificationSuccess({
                title: this.$tc('itb-configurable-listing-filters.general.successTitle'),
                message: this.$tc('itb-configurable-listing-filters.general.updateSuccessMessage')
            });
            
            this.$router.push({ name: 'itb.configurable.listing.filters.list' });
        },

        onChangeLanguage(languageId: string): void {
            Shopware.State.commit('context/setApiLanguageId', languageId);
            const filterForm = this.$refs.filterForm as FilterFormComponent;
            if (filterForm) {
                filterForm.loadFilterWithLanguage(languageId);
            }
        },

        saveOnLanguageChange(): Promise<void> {
            return Promise.resolve();
        },

        abortOnLanguageChange(): Promise<void> {
            return Promise.resolve();
        }
    }
});

export default { name: 'itb-configurable-listing-filters-detail' };