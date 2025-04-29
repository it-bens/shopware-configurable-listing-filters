declare namespace ItbConfigurableListingFilters {
    import sales_channel = EntitySchema.sales_channel;

    interface ListingFilterConfiguration {
        apiAlias: string,
        id: string;
        dalField: string;
        displayName: string;
        position: number | null;
        enabled: boolean;
        twigTemplate: string;
        salesChannelId: string | null;
        salesChannel: sales_channel | null;
    }
}

declare namespace EntitySchema {
    interface Entities {
        itb_lfc_checkbox: itb_lfc_checkbox,
        itb_lfc_multi_select: itb_lfc_multi_select,
        itb_lfc_range: itb_lfc_range,
        itb_lfc_range_interval: itb_lfc_range_interval,
        itb_lfc_range_interval_interval: itb_lfc_range_interval_interval,
    }

    interface itb_lfc_checkbox extends ItbConfigurableListingFilters.ListingFilterConfiguration {
    }

    interface itb_lfc_multi_select extends ItbConfigurableListingFilters.ListingFilterConfiguration {
        sortingOrder: string;
        allowedElements: string[] | null;
        forbiddenElements: string[] | null;
        elementPrefix: string | null;
        elementSuffix: string | null;
        explicitElementSorting: string[] | null;
    }

    interface itb_lfc_range extends ItbConfigurableListingFilters.ListingFilterConfiguration {
        min: number | null;
        max: number | null;
        step: number | null;
        minLabel: string | null;
        maxLabel: string | null;
        unit: string;
    }

    interface itb_lfc_range_interval extends ItbConfigurableListingFilters.ListingFilterConfiguration {
        elementPrefix: string | null;
        elementSuffix: string | null;
        intervals: Array<itb_lfc_range_interval_interval>;
    }

    interface itb_lfc_range_interval_interval {
        id: string;
        min: number | null;
        max: number | null;
        title: string | null;
        position: number;
        rangeIntervalListingFilterConfigurationId: string;
    }
}