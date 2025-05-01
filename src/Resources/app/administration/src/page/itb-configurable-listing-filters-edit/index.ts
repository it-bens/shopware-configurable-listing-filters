import { data, notification } from '@shopware-ag/admin-extension-sdk';
import { getCriteriaByFilterType, getDefaultTwigTemplateByFilterType, getRepositoryByFilterType, getTranslationKeyForCreatePageTitleByFilterType } from '../../mixin/itb-configurable-listing-filters-locator';
import Criteria from '@shopware-ag/admin-extension-sdk/es/data/Criteria';
import type { Entity } from '@shopware-ag/admin-extension-sdk/es/data/_internals/Entity';
import template from './itb-configurable-listing-filters-edit.html.twig';

Shopware.Component.register('itb-configurable-listing-filters-edit', {
    template,

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
            languageId: null,
        };
    },

    computed: {
        createPageTitle() {
            return this.$tc(getTranslationKeyForCreatePageTitleByFilterType(this.$route.params.type));
        },

        defaultTwigTemplate() {
            return getDefaultTwigTemplateByFilterType(this.$route.params.type);
        },

        listingFilterConfigurationRepository() {
            return getRepositoryByFilterType(this.$route.params.type);
        },

        salesChannelCriteria() {
            const criteria = new data.Classes.Criteria();
            criteria.addSorting(Criteria.sort('name', 'ASC'));
            return criteria;
        },

        salesChannelRepository() {
            return data.repository('sales_channel');
        },
    },

    async created() {
        await this.createdComponent();
    },

    methods: {
        async createdComponent() {
            if (!Shopware.State.getters['context/isSystemDefaultLanguage']) {
                Shopware.State.commit('context/resetLanguageToDefault');
            }

            this.isLoading = true;

            await this.loadSalesChannels();
            this.$route.params.id ? await this.loadListingFilterConfiguration() : await this.createListingFilterConfiguration();

            this.isLoading = false;
        },

        async createListingFilterConfiguration(): Promise<void> {
            const listingFilterConfiguration = await this.listingFilterConfigurationRepository.create();
            if (!listingFilterConfiguration) {
                throw new Error('The listing filter configuration could not be created.');
            }

            listingFilterConfiguration.id = Shopware.Utils.createId();
            listingFilterConfiguration.enabled = true;
            listingFilterConfiguration.twigTemplate = this.defaultTwigTemplate;

            this.listingFilterConfiguration = listingFilterConfiguration;
        },

        async loadListingFilterConfiguration(): Promise<void> {
            return this.listingFilterConfigurationRepository.get(
                this.$route.params.id,
                undefined,
                getCriteriaByFilterType(this.$route.params.type),
            ).then(result => {
                this.listingFilterConfiguration = result;
            });
        },

        async loadSalesChannels(): Promise<void> {
            return data.repository('sales_channel').search(this.salesChannelCriteria).then(result => {
                if (!result) {
                    throw new Error('The sales channels could not be loaded.');
                }

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

                notification.dispatch({
                    variant: 'error',
                    title: this.$tc('itb-configurable-listing-filters.general.errorTitle'),
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
