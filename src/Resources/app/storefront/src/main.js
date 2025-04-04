import ItbListingFilterMultiSelectPlugin from "./plugin/listing/itb-listing-filter-multi-select.plugin";

const PluginManager = window.PluginManager;
// The name the plugin is registered with determines the name of the data attributes for the plugin name and the plugin options.
PluginManager.register('ItbListingFilterMultiSelect', ItbListingFilterMultiSelectPlugin, '[data-itb-listing-filter-multi-select]');