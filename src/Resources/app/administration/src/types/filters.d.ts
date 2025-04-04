/**
 * Type definitions for listing filters
 */

declare namespace ITB {
    interface BaseFilter {
        id: string | null;
        dalField: string;
        displayName: string;
        position: number | null;
        enabled: boolean;
        twigTemplate: string;
        salesChannelId: string | null;
    }

    interface CheckboxFilter extends BaseFilter {
        // Checkbox specific properties could be added here
    }

    interface MultiSelectFilter extends BaseFilter {
        displayType: string | null;
        sortingOrder: string;
        allowedElements: string[] | string;
        forbiddenElements: string[] | string;
        elementPrefix: string;
        elementSuffix: string;
        explicitElementSorting: string[] | string;
    }

    interface RangeFilter extends BaseFilter {
        min: number | null;
        max: number | null;
        step: number | null;
        minLabel: string | null;
        maxLabel: string | null;
        unit: string;
    }

    interface SalesChannel {
        id: string;
        name: string;
        // Add other properties as needed
    }

    interface FilterWithType extends BaseFilter {
        filterType: 'checkbox' | 'multiSelect' | 'range';
        filterTypeLabel: string;
    }

    interface FilterFormError {
        code?: string;
        detail?: string;
    }

    interface FilterFormErrors {
        dalField?: FilterFormError;
        displayName?: FilterFormError;
        twigTemplate?: FilterFormError;
        sortingOrder?: FilterFormError;
        [key: string]: FilterFormError | undefined;
    }
}