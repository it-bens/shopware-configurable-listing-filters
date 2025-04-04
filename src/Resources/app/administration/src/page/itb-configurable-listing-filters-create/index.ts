import template from './itb-configurable-listing-filters-create.html.twig';

const { Component, Mixin } = Shopware;

interface CreatePageData {
    isLoading: boolean;
    filterType: string | null;
}

interface FilterFormComponent extends Vue {
    save(): Promise<ITB.BaseFilter | void>;
    $forceUpdate(): void;
}

Component.register('itb-configurable-listing-filters-create', {
    template,

    inject: [
        'repositoryFactory',
        'acl'
    ],
    
    mixins: [
        Mixin.getByName('notification'),
        Mixin.getByName('placeholder')
    ],

    data(): CreatePageData {
        return {
            isLoading: false,
            filterType: null
        };
    },
    
    metaInfo() {
        return {
            title: this.pageTitle
        };
    },
    
    computed: {
        pageTitle(): string {
            switch (this.filterType) {
                case 'checkbox':
                    return this.$tc('itb-configurable-listing-filters.create.titleCheckbox');
                case 'multiSelect':
                    return this.$tc('itb-configurable-listing-filters.create.titleMultiSelect');
                case 'range':
                    return this.$tc('itb-configurable-listing-filters.create.titleRange');
                default:
                    return this.$tc('itb-configurable-listing-filters.create.titleDefault');
            }
        }
    },

    created() {
        this.createdComponent();
    },

    methods: {
        createdComponent(): void {
            this.filterType = this.$route.params.type;
            
            if (!['checkbox', 'multiSelect', 'range'].includes(this.filterType ?? '')) {
                this.createNotificationError({
                    title: this.$tc('itb-configurable-listing-filters.general.errorTitle'),
                    message: this.$tc('itb-configurable-listing-filters.general.invalidFilterType')
                });
                
                this.$router.push({ name: 'itb.configurable.listing.filters.list' });
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
        
        onFilterCreated(): void {
            this.createNotificationSuccess({
                title: this.$tc('itb-configurable-listing-filters.general.successTitle'),
                message: this.$tc('itb-configurable-listing-filters.general.createSuccessMessage')
            });
            
            this.$router.push({ name: 'itb.configurable.listing.filters.list' });
        },

        onChangeLanguage(languageId: string): void {
            Shopware.State.commit('context/setApiLanguageId', languageId);

            const filterForm = this.$refs.filterForm as FilterFormComponent;
            if (filterForm) {
                filterForm.$forceUpdate();
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

export default { name: 'itb-configurable-listing-filters-create' };