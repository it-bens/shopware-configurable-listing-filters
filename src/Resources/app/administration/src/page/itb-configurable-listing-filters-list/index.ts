import template from './itb-configurable-listing-filters-list.html.twig';

const { Component, Mixin } = Shopware;
const { Criteria } = Shopware.Data;

interface ListPageData {
    isLoading: boolean;
    checkboxFilters: Array<ITB.CheckboxFilter>;
    multiSelectFilters: Array<ITB.MultiSelectFilter>;
    rangeFilters: Array<ITB.RangeFilter>;
    salesChannels: Array<ITB.SalesChannel>;
    selectedSalesChannel: string | null;
    showDeleteModal: boolean;
    toBeDeletedFilter: ITB.FilterWithType | null;
    toBeDeletedFilterType: string | null;
}

interface FilterColumn {
    property: string;
    label: string;
    rawData: boolean;
}

interface SalesChannelOption {
    id: string | null;
    name: string;
}

// Erstellen der Komponente
const listPage = Component.register('itb-configurable-listing-filters-list', {
    template,

    inject: [
        'repositoryFactory',
        'acl'
    ],

    mixins: [
        Mixin.getByName('notification'),
        Mixin.getByName('listing'),
        Mixin.getByName('placeholder')
    ],
    
    data(): ListPageData {
        return {
            isLoading: false,
            checkboxFilters: [],
            multiSelectFilters: [],
            rangeFilters: [],
            salesChannels: [],
            selectedSalesChannel: null,
            showDeleteModal: false,
            toBeDeletedFilter: null,
            toBeDeletedFilterType: null
        };
    },

    metaInfo() {
        return {
            title: this.$tc('itb-configurable-listing-filters.list.title')
        };
    },

    computed: {
        checkboxFilterRepository() {
            return this.repositoryFactory.create('itb_listing_filter_configuration_checkbox');
        },

        multiSelectFilterRepository() {
            return this.repositoryFactory.create('itb_listing_filter_configuration_multi_select');
        },

        rangeFilterRepository() {
            return this.repositoryFactory.create('itb_listing_filter_configuration_range');
        },

        salesChannelRepository() {
            return this.repositoryFactory.create('sales_channel');
        },

        filterColumns(): Array<FilterColumn> {
            return [
                {
                    property: 'filterType',
                    label: this.$tc('itb-configurable-listing-filters.list.columnFilterType'),
                    rawData: true
                },
                {
                    property: 'dalField',
                    label: this.$tc('itb-configurable-listing-filters.list.columnDalField'),
                    rawData: true
                },
                {
                    property: 'displayName',
                    label: this.$tc('itb-configurable-listing-filters.list.columnDisplayName'),
                    rawData: true
                },
                {
                    property: 'position',
                    label: this.$tc('itb-configurable-listing-filters.list.columnPosition'),
                    rawData: true
                },
                {
                    property: 'enabled',
                    label: this.$tc('itb-configurable-listing-filters.list.columnEnabled'),
                    rawData: true
                }
            ];
        },

        salesChannelCriteria() {
            const criteria = new Criteria();
            criteria.addSorting(Criteria.sort('name', 'ASC'));
            return criteria;
        },

        allFilters(): Array<ITB.FilterWithType> {
            // Combine all filters and add type information
            const allFilters: Array<ITB.FilterWithType> = [];
            
            this.checkboxFilters.forEach(filter => {
                allFilters.push({
                    ...filter,
                    filterType: 'checkbox',
                    filterTypeLabel: this.$tc('itb-configurable-listing-filters.list.filterTypeCheckbox')
                });
            });
            
            this.multiSelectFilters.forEach(filter => {
                allFilters.push({
                    ...filter,
                    filterType: 'multiSelect',
                    filterTypeLabel: this.$tc('itb-configurable-listing-filters.list.filterTypeMultiSelect')
                });
            });
            
            this.rangeFilters.forEach(filter => {
                allFilters.push({
                    ...filter,
                    filterType: 'range',
                    filterTypeLabel: this.$tc('itb-configurable-listing-filters.list.filterTypeRange')
                });
            });

            // Filter by sales channel if selected
            let filtered = allFilters;
            if (this.selectedSalesChannel) {
                filtered = filtered.filter(filter => filter.salesChannelId === this.selectedSalesChannel || filter.salesChannelId === null);
            }

            // Sort by position
            return filtered.sort((a, b) => {
                const posA = a.position || 999;
                const posB = b.position || 999;
                return posA - posB;
            });
        },

        groupedFilters(): Record<string, Array<ITB.FilterWithType>> {
            const groups: Record<string, Array<ITB.FilterWithType>> = {};
            
            // Group by salesChannelId
            this.allFilters.forEach(filter => {
                const key = filter.salesChannelId || 'global';
                if (!groups[key]) {
                    groups[key] = [];
                }
                groups[key].push(filter);
            });
            
            return groups;
        },

        salesChannelOptions(): Array<SalesChannelOption> {
            const options: Array<SalesChannelOption> = [{
                id: null,
                name: this.$tc('itb-configurable-listing-filters.list.salesChannelAll')
            }];
            
            this.salesChannels.forEach(channel => {
                options.push({
                    id: channel.id,
                    name: channel.name
                });
            });
            
            return options;
        }
    },

    created() {
        this.createdComponent();
    },

    methods: {
        createdComponent(): void {
            this.loadSalesChannels();
            this.loadFilters();
        },
        
        navigateToCreate(filterType: string): void {
            if (!filterType || !['checkbox', 'multiSelect', 'range'].includes(filterType)) {
                this.createNotificationError({
                    title: this.$tc('itb-configurable-listing-filters.general.errorTitle'),
                    message: this.$tc('itb-configurable-listing-filters.general.invalidFilterType')
                });
                return;
            }
            
            this.$router.push({
                name: 'itb.configurable.listing.filters.create',
                params: { type: filterType }
            });
        },

        loadSalesChannels(): void {
            this.salesChannelRepository.search(this.salesChannelCriteria).then(result => {
                this.salesChannels = result;
            });
        },

        loadFilters(): void {
            this.isLoading = true;
            
            const criteria = new Criteria();
            
            const promises = [
                this.loadCheckboxFilters(criteria),
                this.loadMultiSelectFilters(criteria),
                this.loadRangeFilters(criteria)
            ];
            
            Promise.all(promises).then(() => {
                this.isLoading = false;
            });
        },

        loadCheckboxFilters(criteria: typeof Criteria): Promise<Array<ITB.CheckboxFilter>> {
            return this.checkboxFilterRepository.search(criteria).then(result => {
                this.checkboxFilters = result;
                return result;
            });
        },

        loadMultiSelectFilters(criteria: typeof Criteria): Promise<Array<ITB.MultiSelectFilter>> {
            return this.multiSelectFilterRepository.search(criteria).then(result => {
                this.multiSelectFilters = result;
                return result;
            });
        },

        loadRangeFilters(criteria: typeof Criteria): Promise<Array<ITB.RangeFilter>> {
            return this.rangeFilterRepository.search(criteria).then(result => {
                this.rangeFilters = result;
                return result;
            });
        },

        getSalesChannelName(id: string | null): string {
            if (!id) {
                return this.$tc('itb-configurable-listing-filters.list.salesChannelAll');
            }
            
            const channel = this.salesChannels.find(channel => channel.id === id);
            return channel ? channel.name : id;
        },

        onEditFilter(filter: ITB.FilterWithType): void {
            this.$router.push({
                name: 'itb.configurable.listing.filters.detail',
                params: {
                    id: filter.id,
                    filterType: filter.filterType
                }
            });
        },

        onDeleteFilter(filter: ITB.FilterWithType, filterType: string): void {
            this.toBeDeletedFilter = filter;
            this.toBeDeletedFilterType = filterType;
            this.showDeleteModal = true;
        },

        onConfirmDelete(): void {
            let repository;
            
            switch (this.toBeDeletedFilterType) {
                case 'checkbox':
                    repository = this.checkboxFilterRepository;
                    break;
                case 'multiSelect':
                    repository = this.multiSelectFilterRepository;
                    break;
                case 'range':
                    repository = this.rangeFilterRepository;
                    break;
                default:
                    this.createNotificationError({
                        title: this.$tc('itb-configurable-listing-filters.general.errorTitle'),
                        message: this.$tc('itb-configurable-listing-filters.general.deletionErrorMessage')
                    });
                    return;
            }
            
            if (this.toBeDeletedFilter && this.toBeDeletedFilter.id) {
                repository.delete(this.toBeDeletedFilter.id).then(() => {
                    this.loadFilters();
                    this.showDeleteModal = false;
                    this.createNotificationSuccess({
                        title: this.$tc('itb-configurable-listing-filters.general.successTitle'),
                        message: this.$tc('itb-configurable-listing-filters.general.deleteSuccessMessage')
                    });
                }).catch(() => {
                    this.createNotificationError({
                        title: this.$tc('itb-configurable-listing-filters.general.errorTitle'),
                        message: this.$tc('itb-configurable-listing-filters.general.deletionErrorMessage')
                    });
                });
            }
        },

        onCloseDeleteModal(): void {
            this.showDeleteModal = false;
            this.toBeDeletedFilter = null;
            this.toBeDeletedFilterType = null;
        },

        onSalesChannelChange(id: string | null): void {
            this.selectedSalesChannel = id;
        },

        onChangeLanguage(languageId: string): void {
            Shopware.State.commit('context/setApiLanguageId', languageId);
            this.loadFilters();
        },

        saveOnLanguageChange(): Promise<void> {
            return Promise.resolve();
        },

        abortOnLanguageChange(): Promise<void> {
            return Promise.resolve();
        }
    }
});

// Registrierung der Komponente
Component.register('itb-configurable-listing-filters-list', listPage);

// Export für externe Nutzung
export default listPage;