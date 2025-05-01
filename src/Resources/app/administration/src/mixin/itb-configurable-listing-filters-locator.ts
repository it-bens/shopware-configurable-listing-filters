import { data } from '@shopware-ag/admin-extension-sdk';
import type repositoryFactory from '@shopware-ag/admin-extension-sdk/es/data/repository';

type CheckboxRepository = ReturnType<typeof repositoryFactory<'itb_lfc_checkbox'>>;
type MultiSelectRepository = ReturnType<typeof repositoryFactory<'itb_lfc_multi_select'>>;
type RangeRepository = ReturnType<typeof repositoryFactory<'itb_lfc_range'>>;
type RangeIntervalRepository = ReturnType<typeof repositoryFactory<'itb_lfc_range_interval'>>;
type ListingFilterRepository = CheckboxRepository | MultiSelectRepository | RangeRepository | RangeIntervalRepository;

export function getRepositoryByFilterType(listingFilterConfigurationType: string): ListingFilterRepository {
    switch (listingFilterConfigurationType) {
        case 'checkbox':
            return data.repository('itb_lfc_checkbox');
        case 'multi-select':
            return data.repository('itb_lfc_multi_select');
        case 'range':
            return data.repository('itb_lfc_range');
        case 'range-interval':
            return data.repository('itb_lfc_range_interval');
        default:
            throw new Error(`Invalid listing filter configuration type: ${listingFilterConfigurationType}`);
    }
}

export function getRepositoryByEntityName(listingFilterConfigurationType: string): ListingFilterRepository {
    switch (listingFilterConfigurationType) {
        case 'itb_lfc_checkbox':
            return data.repository('itb_lfc_checkbox');
        case 'itb_lfc_multi_select':
            return data.repository('itb_lfc_multi_select');
        case 'itb_lfc_range':
            return data.repository('itb_lfc_range');
        case 'itb_lfc_range_interval':
            return data.repository('itb_lfc_range_interval');
        default:
            throw new Error(`Invalid entity name: ${listingFilterConfigurationType}`);
    }
}

export function getDefaultTwigTemplateByFilterType(listingFilterConfigurationType: string) {
    switch (listingFilterConfigurationType) {
        case 'checkbox':
            return '@Storefront/storefront/component/listing/filter/filter-boolean.html.twig';
        case 'multi-select':
            return '@Storefront/storefront/component/listing/filter/filter-multi-select.html.twig';
        case 'range':
            return '@Storefront/storefront/component/listing/filter/filter-range.html.twig';
        case 'range-interval':
            return '@Storefront/storefront/component/listing/filter/filter-multi-select.html.twig';
        default:
            throw new Error(`Invalid listing filter configuration type: ${listingFilterConfigurationType}`);
    }
}

export function getTranslationKeyForCreatePageTitleByFilterType(listingFilterConfigurationType: string) {
    switch (listingFilterConfigurationType) {
        case 'checkbox':
            return 'itb-configurable-listing-filters.form.checkbox.createPageTitle';
        case 'multi-select':
            return 'itb-configurable-listing-filters.form.multiSelect.createPageTitle';
        case 'range':
            return 'itb-configurable-listing-filters.form.range.createPageTitle';
        case 'range-interval':
            return 'itb-configurable-listing-filters.form.rangeInterval.createPageTitle';
        default:
            throw new Error(`Invalid listing filter configuration type: ${listingFilterConfigurationType}`);
    }
}

export function getFilterTypeByEntityName(entityName: string) {
    switch (entityName) {
        case 'itb_lfc_checkbox':
            return 'checkbox';
        case 'itb_lfc_multi_select':
            return 'multi-select';
        case 'itb_lfc_range':
            return 'range';
        case 'itb_lfc_range_interval':
            return 'range-interval';
        default:
            throw new Error(`Invalid entity name: ${entityName}`);
    }
}

export function getCriteriaByFilterType(listingFilterConfigurationType: string) {
    const criteria = new data.Classes.Criteria();
    criteria.addAssociation('salesChannel');

    switch (listingFilterConfigurationType) {
        case 'checkbox':
            return criteria;
        case 'multi-select':
            return criteria;
        case 'range':
            return criteria;
        case 'range-interval':
            criteria.addAssociation('intervals');
            criteria.getAssociation('intervals.rangeIntervalListingFilterConfiguration');

            return criteria;
        default:
            throw new Error(`Invalid listing filter configuration type: ${listingFilterConfigurationType}`);
    }
}

export function getCriteriaByEntityName(listingFilterConfigurationType: string) {
    const criteria = new data.Classes.Criteria();
    criteria.addAssociation('salesChannel');

    switch (listingFilterConfigurationType) {
        case 'itb_lfc_checkbox':
            return criteria;
        case 'itb_lfc_multi_select':
            return criteria;
        case 'itb_lfc_range':
            return criteria;
        case 'itb_lfc_range_interval':
            criteria.addAssociation('intervals');
            criteria.getAssociation('intervals.rangeIntervalListingFilterConfiguration');

            return criteria;
        default:
            throw new Error(`Invalid entity name: ${listingFilterConfigurationType}`);
    }
}

export function getAllowedPropertyTypesByFilterType(listingFilterConfigurationType: string) {
    switch (listingFilterConfigurationType) {
        case 'checkbox':
            return ['boolean'];
        case 'multi-select':
            return ['string'];
        case 'range':
            return ['int', 'float'];
        case 'range-interval':
            return ['int', 'float'];
        default:
            throw new Error(`Invalid listing filter configuration type: ${listingFilterConfigurationType}`);
    }
}
