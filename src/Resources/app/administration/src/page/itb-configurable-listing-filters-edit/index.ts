import template from './itb-configurable-listing-filters-edit.html.twig';
import {Entity} from "@shopware-ag/admin-extension-sdk/es/data/_internals/Entity";

const { Mixin, Context } = Shopware;
const { Criteria } = Shopware.Data;

// eslint-disable-next-line sw-deprecation-rules/private-feature-declarations
Shopware.Component.register('itb-configurable-listing-filters-edit', {
    template,

    inject: [
        'repositoryFactory',
    ],

    mixins: [
        Mixin.getByName('notification'),
        Mixin.getByName('itbConfigurableListingFiltersLocator'),
    ],

    data(): {
        isLoading: boolean;
        isSaving: boolean;
        isSaveSuccessful: boolean;
        listingFilterConfiguration: (
            EntitySchema.itb_lfc_checkbox |
            EntitySchema.itb_lfc_multi_select |
            EntitySchema.itb_lfc_range |
            EntitySchema.itb_lfc_range_interval
            ) & Entity<any> | null;
        salesChannels: Array<EntitySchema.sales_channel>;
        languageId: string | null;
    } {
        return {
            isLoading: false,
            isSaving: false,
            isSaveSuccessful: false,
            listingFilterConfiguration: null,
            salesChannels: [],
            languageId: null
        };
    },

    computed: {
        createPageTitle() {
            return this.$tc(this.getTranslationKeyForCreatePageTitleByFilterType(this.$route.params.type));
        },

        defaultTwigTemplate() {
            return this.getDefaultTwigTemplateByFilterType(this.$route.params.type);
        },

        isSystemLanguage() {
            return this.languageId === Context.api.systemLanguageId;
        },

        languageRepository() {
            return this.repositoryFactory.create('language');
        },

        listingFilterConfigurationRepository() {
            return this.getRepositoryByFilterType(this.$route.params.type, this.repositoryFactory);
        },

        salesChannelCriteria() {
            const criteria = new Criteria();
            criteria.addSorting(Criteria.sort('name', 'ASC'));
            return criteria;
        },

        salesChannelRepository() {
            return this.repositoryFactory.create('sales_channel');
        },
    },

    async created() {
        await this.createdComponent();
    },

    methods: {
        async createdComponent() {
            if (!Shopware.State.getters['context/isSystemDefaultLanguage']) {
                Shopware.Context.api.languageId = Shopware.Context.api.systemLanguageId;
            }

            this.isLoading = true;

            await this.loadSalesChannels();
            this.$route.params.id ? await this.loadListingFilterConfiguration() : this.createListingFilterConfiguration();

            this.isLoading = false;
        },

        createListingFilterConfiguration(): void {
            const listingFilterConfiguration = this.listingFilterConfigurationRepository.create();
            listingFilterConfiguration.id = Shopware.Utils.createId();
            listingFilterConfiguration.enabled = true;
            listingFilterConfiguration.twigTemplate = this.defaultTwigTemplate

            this.listingFilterConfiguration = listingFilterConfiguration;
        },

        async loadListingFilterConfiguration(): Promise<void> {
            return this.listingFilterConfigurationRepository.get(
                this.$route.params.id,
                undefined,
                this.getCriteriaByFilterType(this.$route.params.type),
            ).then(result => {
                this.listingFilterConfiguration = result;
            })
        },

        async loadSalesChannels(): Promise<void> {
            return this.salesChannelRepository.search(this.salesChannelCriteria).then(result => {
                this.salesChannels = result;
            });
        },

        onLanguageChangeSave() {
            return this.onSave();
        },

        onLanguageChangeAbort() {
            if (this.listingFilterConfiguration === null) {
                return false;
            }

            return this.listingFilterConfigurationRepository.hasChanges(this.listingFilterConfiguration);
        },

        async onLanguageChange(languageId: string) {
            this.languageId = languageId;

            this.isLoading = true;
            await this.loadListingFilterConfiguration();
        },

        async onSave() {
            this.isLoading = true;
            this.isSaveSuccessful = false;

            const languageId = this.languageId;
            const context = { ...Shopware.Context.api, ...{ languageId } };

            const listingFilterConfiguration = this.listingFilterConfiguration;
            if (!listingFilterConfiguration) {
                throw new Error('The listing filter configuration should not be null at this point.');
            }

            return this.listingFilterConfigurationRepository.save(listingFilterConfiguration, context).then((response) => {
                this.isSaveSuccessful = true;

                return response;
            }).catch(() => {
                this.isSaveSuccessful = false;

                this.createNotificationError({
                    message: this.$tc('sw-customer.detail.messageSaveError'),
                });
            }).finally(() => {
                this.isLoading = false;
            });
        },

        async saveFinish(): Promise<void> {
            this.isSaveSuccessful = false;
            await this.$router.push({ name: 'itb.configurable-listing-filters.list' });
        },
    },
});
