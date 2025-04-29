import template from './itb-configurable-listing-filters-form-dal-path-field.html.twig';
import {Property} from "src/core/data/entity-definition.data";

const { Mixin } = Shopware;
const utils = Shopware.Utils;

interface Properties {
    [key: string]: Property;
}

interface DefinitionPropertyOption {
    id: string;
    name: string;
}

// eslint-disable-next-line sw-deprecation-rules/private-feature-declarations
Shopware.Component.register('itb-configurable-listing-filters-form-dal-path-field', {
    template,

    mixins: [
        Mixin.getByName('itbConfigurableListingFiltersLocator'),
    ],

    model: {
        prop: 'value',
        event: 'change',
    },

    props: {
        label: {
            type: String,
            required: false,
            default: null,
        },
        helpText: {
            type: String,
            required: false,
            default: null,
        },
        required: {
            type: Boolean,
            required: false,
            default: false,
        },
        filterType: {
            type: String,
            required: true,
        },
        value: {
            type: String,
            required: true,
            default: '',
        },
    },

    data(): {
        id: string,
        containerStyle: string,
        selectedDalPath: Array<string>
    } {
        return {
            id: utils.createId(),
            containerStyle: 'grid-template-columns: 1fr; gap: 0px 30px; place-items: stretch;',
            selectedDalPath: this.value.split('.').filter(dalPathPart => dalPathPart !== ''),
        };
    },

    computed: {
        identification() {
            return `sw-field--${this.id}`;
        },

        swFieldLabelClasses() {
            return {
                'is--required': this.required,
            };
        },

        salesChannelProductProperties(): Properties {
            const productDefinition = Shopware.EntityDefinition.get('product')
            const properties = productDefinition.properties;
            const cheapestPriceProperty: Property = {
                type: 'float',
            }
            const cheapestPricePropertyKey = 'cheapestPrice';
            properties[cheapestPricePropertyKey] = cheapestPriceProperty;

            return properties;
        },

        definitionPropertyOptions(): Array<DefinitionPropertyOption> {
            let definitionProperties = this.salesChannelProductProperties;
            let selectedDalPath = this.selectedDalPath.slice();

            let definitionPropertyOptions: DefinitionPropertyOption[] = this.buildDefinitionPropertyOptionsFromDefinitionProperties(definitionProperties)

            selectedDalPath.forEach((dalPathPart: string) => {
                const property = definitionProperties[dalPathPart];

                if (property && property.type === 'association' && (typeof property.entity === 'string')) {
                    const definition = Shopware.EntityDefinition.get(property.entity);
                    definitionPropertyOptions = this.buildDefinitionPropertyOptionsFromDefinitionProperties(definition.properties);

                    return;
                }

                definitionPropertyOptions = [];
            });

            this.updateContainerStyle(definitionPropertyOptions);

            return definitionPropertyOptions;
        },
    },

    watch: {
        value(value: string) {
            this.selectedDalPath = value.split('.').filter(dalPathPart => dalPathPart !== '');
        },
    },

    methods: {
        updateContainerStyle(definitionPropertyOptions: DefinitionPropertyOption[]) {
            const columnCount = this.selectedDalPath.length + (definitionPropertyOptions.length > 0 ? 1 : 0);
            const columns = '1fr '.repeat(columnCount).trim();

            this.containerStyle = `grid-template-columns: ${columns}; gap: 0px 30px; place-items: stretch;`
        },

        filterAllowedProperties(properties: Properties): Properties {
            const allowedProperties: Properties = {}

            Object.keys(properties).forEach((propertyKey) => {
                if (properties[propertyKey].type === undefined) {
                    return;
                }

                if (properties[propertyKey].type === 'association') {
                    if (properties[propertyKey].relation === 'one_to_one' || properties[propertyKey].relation === 'many_to_one') {
                        allowedProperties[propertyKey] = properties[propertyKey];
                    }
                }

                if (this.getAllowedPropertyTypesByFilterType(this.filterType).includes(properties[propertyKey].type)) {
                    allowedProperties[propertyKey] = properties[propertyKey];
                }
            });

            return allowedProperties;
        },

        buildDefinitionPropertyOptionsFromDefinitionProperties(properties: Properties): DefinitionPropertyOption[] {
            const definitionPropertyOptions: DefinitionPropertyOption[] = [];
            Object.keys(this.filterAllowedProperties(properties)).forEach((propertyKey) => {
                definitionPropertyOptions.push({
                    id: propertyKey,
                    name: propertyKey,
                });
            });

            return definitionPropertyOptions;
        },

        onChange(value: string): void {
            this.selectedDalPath.push(value);
            this.$emit('change', this.selectedDalPath.join('.'));
        },

        onDeleteLastDalPathPart(): void {
            if (this.selectedDalPath.length > 0) {
                this.selectedDalPath.pop();
                this.$emit('change', this.selectedDalPath.join('.'));
            }
        }
    }
});
