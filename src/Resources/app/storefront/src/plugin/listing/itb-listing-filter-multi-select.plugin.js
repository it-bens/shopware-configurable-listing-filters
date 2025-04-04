import FilterMultiSelectPlugin from 'src/plugin/listing/filter-multi-select.plugin';
import DomAccess from 'src/helper/dom-access.helper';
import Iterator from 'src/helper/iterator.helper';

export default class ItbListingFilterMultiSelectPlugin extends FilterMultiSelectPlugin {
    /**
     * @return {Array}
     * @public
     */
    getLabels() {
        const activeCheckboxes =
            DomAccess.querySelectorAll(this.el, `${this.options.checkboxSelector}:checked`, false);
        const mainFilterButton =
            DomAccess.querySelector(this.el, this.options.mainFilterButtonSelector);
        const pluginOptions =
            JSON.parse(mainFilterButton.parentElement.dataset['itbListingFilterMultiSelectOptions']);
        const shouldLabelInActiveFilterBePrefixedListWithDisplayName =
            pluginOptions.shouldLabelInActiveFilterBePrefixedListWithDisplayName ?? false;
        const filterDisplayName = pluginOptions.displayName ?? '';
        const labelPrefix = shouldLabelInActiveFilterBePrefixedListWithDisplayName ? filterDisplayName + ': ' : '';

        let labels = [];

        if (activeCheckboxes) {
            Iterator.iterate(activeCheckboxes, (checkbox) => {
                labels.push({
                    label: labelPrefix + checkbox.dataset.label,
                    id: checkbox.id,
                });
            });
        } else {
            labels = [];
        }

        return labels;
    }

    /**
     * @public
     */
    refreshDisabledState(filter) {
        const disabledFilter = filter[this.options.name];

        if (!disabledFilter.buckets || disabledFilter.buckets.length < 1) {
            this.disableFilter();
            return;
        }

        this.enableFilter();

        const filterName = this.options.name;
        const elementPrefix = this.options.elementPrefix ?? '';
        const elementSuffix = this.options.elementSuffix ?? '';
        this._disableInactiveFilterOptions(disabledFilter.buckets.map(bucket => `${filterName}_${elementPrefix}${bucket.key}${elementSuffix}`));
    }
}
