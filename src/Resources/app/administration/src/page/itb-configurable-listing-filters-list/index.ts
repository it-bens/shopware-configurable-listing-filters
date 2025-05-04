import { data, notification } from '@shopware-ag/admin-extension-sdk';
import { getCriteriaByEntityName, getFilterTypeByEntityName, getRepositoryByEntityName } from '../../mixin/itb-configurable-listing-filters-locator';
import Criteria from '@shopware-ag/admin-extension-sdk/es/data/Criteria';
import type { Entity } from '@shopware-ag/admin-extension-sdk/es/data/_internals/Entity';
import type EntityCollection from '@shopware-ag/admin-extension-sdk/es/data/_internals/EntityCollection';
import template from './itb-configurable-listing-filters-list.html.twig';

type itb_lfc = 'itb_lfc_checkbox' | 'itb_lfc_multi_select' | 'itb_lfc_range' | 'itb_lfc_range_interval';

interface DataGridColumn {
    property: string;
    label: string;
    rawData: boolean;
}

interface DataGridRecord {
    type: string;
    id: string;
    dalField: string;
    displayName: string;
    salesChannel: string;
    position: string;
    enabled: boolean;
}

interface SalesChannelOption {
    id: string | null;
    name: string;
}

export default Shopware.Component.wrapComponentConfig({
    template,

    data(): {
        isLoading: boolean;
        checkboxListingFilterConfigurations: Array<EntitySchema.itb_lfc_checkbox>;
        multiSelectListingFilterConfigurations: Array<EntitySchema.itb_lfc_multi_select>;
        rangeListingFilterConfigurations: Array<EntitySchema.itb_lfc_range>;
        rangeIntervalListingFilterConfigurations: Array<EntitySchema.itb_lfc_range_interval>;
        salesChannels: Array<EntitySchema.sales_channel>;
        selectedSalesChannelForFiltering: string | null;
    } {
        return {
            isLoading: false,
            checkboxListingFilterConfigurations: [],
            multiSelectListingFilterConfigurations: [],
            rangeListingFilterConfigurations: [],
            rangeIntervalListingFilterConfigurations: [],
            salesChannels: [],
            selectedSalesChannelForFiltering: null,
        };
    },

    metaInfo() {
        return {
            title: this.$tc('itb-configurable-listing-filters.list.title'),
        };
    },

    computed: {
        salesChannelRepository() {
            return data.repository('sales_channel');
        },

        dataGridColumns(): Array<DataGridColumn> {
            return [
                {
                    property: 'dalField',
                    label: this.$tc('itb-configurable-listing-filters.list.dataGrid.column.dalField'),
                    rawData: true,
                },
                {
                    property: 'displayName',
                    label: this.$tc('itb-configurable-listing-filters.list.dataGrid.column.displayName'),
                    rawData: true,
                },
                {
                    property: 'salesChannel',
                    label: this.$tc('itb-configurable-listing-filters.list.dataGrid.column.salesChannel'),
                    rawData: true,
                },
                {
                    property: 'position',
                    label: this.$tc('itb-configurable-listing-filters.list.dataGrid.column.position'),
                    rawData: true,
                },
                {
                    property: 'enabled',
                    label: this.$tc('itb-configurable-listing-filters.list.dataGrid.column.enabled'),
                    rawData: true,
                },
            ];
        },

        salesChannelCriteria() {
            const criteria = new Criteria();
            criteria.addSorting(Criteria.sort('name', 'ASC'));

            return criteria;
        },

        listingFilterConfigurations(): Array<ItbConfigurableListingFilters.ListingFilterConfiguration> {
            return [
                ...this.checkboxListingFilterConfigurations,
                ...this.multiSelectListingFilterConfigurations,
                ...this.rangeListingFilterConfigurations,
                ...this.rangeIntervalListingFilterConfigurations,
            ];
        },

        dataGridRecordsForSalesChannel(): Array<DataGridRecord> {
            const records: Array<DataGridRecord> = [];

            const listingFilterConfigurations = this.listingFilterConfigurationsForSalesChannel(this.selectedSalesChannelForFiltering);
            listingFilterConfigurations.forEach((listingFilterConfiguration: Entity<itb_lfc>) => {
                records.push({
                    type: listingFilterConfiguration.apiAlias.replace('_foreign_keys_extension', ''),
                    id: listingFilterConfiguration.id,
                    dalField: listingFilterConfiguration.dalField,
                    displayName: listingFilterConfiguration.displayName,
                    salesChannel: this.getSalesChannelName(listingFilterConfiguration.salesChannelId),
                    position: listingFilterConfiguration.position ? listingFilterConfiguration.position.toString() : '',
                    enabled: listingFilterConfiguration.enabled,
                });
            });

            return records;
        },

        salesChannelFilterOptions(): Array<SalesChannelOption> {
            const options: Array<SalesChannelOption> = [{
                id: null,
                name: this.$tc('itb-configurable-listing-filters.list.allSalesChannels'),
            }];

            this.salesChannels.forEach((salesChannel: Entity<'sales_channel'>) => {
                options.push({
                    id: salesChannel.id,
                    name: salesChannel.name,
                });
            });

            return options;
        },
    },

    async created() {
        await this.createdComponent();
    },

    methods: {
        async createdComponent(): Promise<void> {
            const promises: Array<Promise<void>> = [];
            promises.push(this.loadSalesChannels());
            promises.push(this.loadListingFilterConfigurations());

            await Promise.all(promises);
        },

        listingFilterConfigurationsForSalesChannel(salesChannelId: string|null): Array<ItbConfigurableListingFilters.ListingFilterConfiguration> {
            let listingFilterConfigurations = this.listingFilterConfigurations;

            if (typeof salesChannelId === 'string') {
                listingFilterConfigurations = listingFilterConfigurations.filter((listingFilterConfiguration: Entity<itb_lfc>) => listingFilterConfiguration.salesChannelId === salesChannelId || listingFilterConfiguration.salesChannelId === null);
            }

            return listingFilterConfigurations.sort((a: Entity<itb_lfc>, b: Entity<itb_lfc>) => {
                const posA = a.position || 999;
                const posB = b.position || 999;
                return posA - posB;
            });
        },

        async loadSalesChannels(): Promise<void> {
            return this.salesChannelRepository.search(this.salesChannelCriteria).then((result: EntityCollection<'sales_channel'>) => {
                result.forEach((salesChannel: Entity<'sales_channel'>) => {
                    this.salesChannels.push(salesChannel);
                });
            });
        },

        async loadListingFilterConfigurations(): Promise<void> {
            this.isLoading = true;

            const promises: Array<Promise<void>> = [];

            const checkboxListingFilterConfigurationRepository = data.repository('itb_lfc_checkbox');
            const checkboxListingFilterConfigurationCriteria = getCriteriaByEntityName('itb_lfc_checkbox');
            promises.push(checkboxListingFilterConfigurationRepository.search(checkboxListingFilterConfigurationCriteria).then(result => {
                if (!result) {
                    throw new Error('No result found for checkbox listing filter configuration');
                }

                this.checkboxListingFilterConfigurations = [];
                result.forEach((listingFilterConfiguration) => {
                    this.checkboxListingFilterConfigurations.push(listingFilterConfiguration);
                });
            }));

            const multiSelectListingFilterConfigurationRepository = data.repository('itb_lfc_multi_select');
            const multiSelectListingFilterConfigurationCriteria = getCriteriaByEntityName('itb_lfc_multi_select');
            promises.push(multiSelectListingFilterConfigurationRepository.search(multiSelectListingFilterConfigurationCriteria).then(result => {
                if (!result) {
                    throw new Error('No result found for multi-select listing filter configuration');
                }

                this.multiSelectListingFilterConfigurations = [];
                result.forEach((listingFilterConfiguration) => {
                    this.multiSelectListingFilterConfigurations.push(listingFilterConfiguration);
                });
            }));

            const rangeListingFilterConfigurationRepository = data.repository('itb_lfc_range');
            const rangeListingFilterConfigurationCriteria = getCriteriaByEntityName('itb_lfc_range');
            promises.push(rangeListingFilterConfigurationRepository.search(rangeListingFilterConfigurationCriteria).then(result => {
                if (!result) {
                    throw new Error('No result found for range listing filter configuration');
                }

                this.rangeListingFilterConfigurations = [];
                result.forEach((listingFilterConfiguration) => {
                    this.rangeListingFilterConfigurations.push(listingFilterConfiguration);
                });
            }));

            const rangeIntervalListingFilterConfigurationRepository = data.repository('itb_lfc_range_interval');
            const rangeIntervalListingFilterConfigurationCriteria = getCriteriaByEntityName('itb_lfc_range_interval');
            promises.push(rangeIntervalListingFilterConfigurationRepository.search(rangeIntervalListingFilterConfigurationCriteria).then(result => {
                if (!result) {
                    throw new Error('No result found for range interval listing filter configuration');
                }

                this.rangeIntervalListingFilterConfigurations = [];
                result.forEach((listingFilterConfiguration) => {
                    this.rangeIntervalListingFilterConfigurations.push(listingFilterConfiguration);
                });
            }));

            return Promise.all(promises).then(() => {
                this.isLoading = false;
            }).catch(error => {
                console.error('Error loading filters:', error);
                this.isLoading = false;
            });
        },

        getSalesChannelName(id: string | null): string {
            if (!id) {
                return this.$tc('itb-configurable-listing-filters.list.allSalesChannels');
            }

            const salesChannel = this.salesChannels.find((channel: Entity<'sales_channel'>) => channel.id === id);
            return salesChannel ? salesChannel.name : id;
        },

        async onEditListingFilterConfiguration(dataGridRecord: DataGridRecord): Promise<void> {
            await this.$router.push({
                name: 'itb.configurable-listing-filters.edit',
                params: {
                    type: getFilterTypeByEntityName(dataGridRecord.type),
                    id: dataGridRecord.id,
                },
            });
        },

        async onDeleteListingFilterConfiguration(dataGridRecord: DataGridRecord): Promise<void> {
            const repository = getRepositoryByEntityName(dataGridRecord.type);

            this.isLoading = true;
            await repository.delete(dataGridRecord.id).then(() => {
                this.loadListingFilterConfigurations();
                notification.dispatch({
                    variant: 'success',
                    title: this.$tc('itb-configurable-listing-filters.general.successTitle'),
                    message: this.$tc('itb-configurable-listing-filters.general.deleteSuccessMessage'),
                });
            }).catch((error: Error) => {
                console.error('Delete operation failed:', error);
                notification.dispatch({
                    variant: 'error',
                    title: this.$tc('itb-configurable-listing-filters.general.errorTitle'),
                    message: this.$tc('itb-configurable-listing-filters.general.deletionErrorMessage'),
                });
            }).finally(() => {
                this.isLoading = false;
            });
        },

        onSalesChannelFilterChange(id: string | null): void {
            this.selectedSalesChannelForFiltering = id;
        },

        async onChangeLanguage(languageId: string): Promise<void> {
            Shopware.State.commit('context/setApiLanguageId', languageId);
            await this.loadListingFilterConfigurations();
        },

        saveOnLanguageChange(): void {},

        abortOnLanguageChange(): void {},
    },
});
