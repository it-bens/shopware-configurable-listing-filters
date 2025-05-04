import { data } from '@shopware-ag/admin-extension-sdk';
import template from './itb-configurable-listing-filters-form-range-interval-interval.html.twig';

const { mapPropertyErrors } = Shopware.Component.getComponentHelper();

interface DataGridColumn {
    property: string;
    label: string;
    inlineEdit: string;
    width: string;
}

interface DataGridRecord {
    id: string;
    min: number | null;
    max: number | null;
    title: string | null;
    position: number;
}

export default Shopware.Component.wrapComponentConfig({
    template,

    props: {
        listingFilterConfiguration: {
            type: Object as () => EntitySchema.itb_lfc_range_interval,
            required: false,
            default: null,
        },
    },

    computed: {
        ...mapPropertyErrors('listingFilterConfiguration', [
            'intervals',
        ]),

        dataGridColumns(): Array<DataGridColumn> {
            return [
                {
                    property: 'min',
                    label: this.$tc('itb-configurable-listing-filters.form.rangeInterval.interval.labelMin'),
                    inlineEdit: 'number',
                    width: '110px',
                },
                {
                    property: 'max',
                    label: this.$tc('itb-configurable-listing-filters.form.rangeInterval.interval.labelMax'),
                    inlineEdit: 'number',
                    width: '110px',
                },
                {
                    property: 'title',
                    label: this.$tc('itb-configurable-listing-filters.form.rangeInterval.interval.labelTitle'),
                    inlineEdit: 'string',
                    width: '110px',
                },
                {
                    property: 'position',
                    label: this.$tc('itb-configurable-listing-filters.form.rangeInterval.interval.labelPosition'),
                    inlineEdit: 'number',
                    width: '110px',
                },
            ];
        },

        dataGridRecords(): Array<DataGridRecord> {
            const sortedIntervals = [...this.listingFilterConfiguration.intervals].sort((a, b) => {
                return a.position - b.position;
            });

            const records: Array<DataGridRecord> = [];
            sortedIntervals.forEach(interval => {
                records.push({
                    id: interval.id,
                    min: interval.min,
                    max: interval.max,
                    title: interval.title,
                    position: interval.position,
                });
            });

            return records;
        },

        intervalRepository() {
            return data.repository('itb_lfc_range_interval_interval');
        },
    },

    methods: {
        onInlineEditFinish(item: DataGridRecord) {
            if (this.listingFilterConfiguration.intervals && this.listingFilterConfiguration.intervals.length > 0) {
                this.listingFilterConfiguration.intervals.forEach(existingInterval => {
                    if (item.id === existingInterval.id) {
                        existingInterval.min = item.min;
                        existingInterval.max = item.max;
                        existingInterval.title = item.title;
                        existingInterval.position = item.position;
                    }
                });
            }
        },

        async onIntervalAdd(): Promise<void> {
            const interval = await this.intervalRepository.create();
            if (!interval) {
                throw new Error('Failed to create interval');
            }

            let highestPosition = 0;
            if (this.listingFilterConfiguration.intervals && this.listingFilterConfiguration.intervals.length > 0) {
                this.listingFilterConfiguration.intervals.forEach(existingInterval => {
                    if (existingInterval.position > highestPosition) {
                        highestPosition = existingInterval.position;
                    }
                });
            }

            interval.min = null;
            interval.max = null;
            interval.title = null;
            interval.position = highestPosition + 1;

            this.listingFilterConfiguration.intervals.push(interval);
        },

        onIntervalDelete(interval: DataGridRecord): void {
            const index = this.listingFilterConfiguration.intervals.findIndex(item => item.id === interval.id);

            if (index !== -1) {
                this.listingFilterConfiguration.intervals.splice(index, 1);
            }
        },
    },
});
