import { defineConfigWithVueTs, vueTsConfigs } from '@vue/eslint-config-typescript'
import { resolve } from 'path';
import eslint from '@eslint/js';
import pluginVue from 'eslint-plugin-vue'
import tseslint from 'typescript-eslint';
import vue from 'eslint-plugin-vue';
import swESLintBase from '@shopware-ag/eslint-config-base';
import importPlugin from 'eslint-plugin-import' ;
import stylistic from '@stylistic/eslint-plugin';

process.env.ADMIN_PATH =
    process.env.ADMIN_PATH ??
    resolve('../../../../vendor/shopware/administration/Resources/app/administration');

export default tseslint.config(
    eslint.configs.recommended,
    ...vue.configs['flat/recommended'],
    ...defineConfigWithVueTs(
        pluginVue.configs['flat/vue2-recommended'],
        vueTsConfigs.recommended,
    ),
    {
        files: ['**/*.ts', '**/*.js'],
        ignores: [
            'build/*.js',
            'src/global.types.ts',
            'src/types/*.ts',
            '**/*.d.ts'
        ],

        plugins: { import: importPlugin, stylistic },

        languageOptions: {
            ecmaVersion: 'latest',
            globals: {
                Shopware: true,
            },
            parserOptions: {
                projectService: true,
                tsconfigRootDir: import.meta.dirname,
            },
        },

        settings: {
            'import/resolver': {
                node: {},
                typescript: {
                    project: './tsconfig.json',
                },
            },
        },

        rules: {
            ...swESLintBase.rules,
            indent: 'off',
            'comma-dangle': 'off',
            'max-len': 'off',

            'no-console': ['error', { allow: ['warn', 'error'] }],
            'internal-rules/no-src-imports': 'off',

            /* import rules */
            // lets depend on shopware's deps (vue and @vue/test-utils)
            'import/no-extraneous-dependencies': 'off',
            'import/no-useless-path-segments': 'off',
            'import/extensions': [
                'error',
                'ignorePackages',
                { js: 'never', ts: 'never' },
            ],
            /* import rules */

            /* stylistic rules */
            'stylistic/semi': ['error', 'always'],
            'stylistic/indent': ['error', 4, { SwitchCase: 1 }],
            'stylistic/member-delimiter-style': ['error'],
            'stylistic/no-multi-spaces': ['error'],
            'stylistic/object-curly-spacing': ['error', 'always'],
            'stylistic/space-before-function-paren': ['error', {
                anonymous: 'always',
                named: 'never',
                asyncArrow: 'always',
            }],
            'stylistic/spaced-comment': ['error', 'always'],
            'stylistic/no-tabs': ['error'],
            'stylistic/no-mixed-spaces-and-tabs': ['error'],
            'stylistic/max-len': 'off',
            'stylistic/quote-props': ['error', 'as-needed'],
            'stylistic/no-extra-semi': ['error'],
            'stylistic/comma-dangle': ['error', 'always-multiline'],
            /* stylistic rules */

            /* typescript rules */
            '@typescript-eslint/ban-ts-comment': ['error', { 'ts-expect-error': false }],
            '@typescript-eslint/no-unsafe-member-access': 'error',
            '@typescript-eslint/no-unsafe-call': 'error',
            '@typescript-eslint/no-unsafe-assignment': 'error',
            '@typescript-eslint/no-unsafe-return': 'error',
            '@typescript-eslint/no-unsafe-argument': 'error',
            '@typescript-eslint/explicit-module-boundary-types': 'off',
            '@typescript-eslint/prefer-ts-expect-error': 'error',
            '@typescript-eslint/no-floating-promises': 'off',
            '@typescript-eslint/no-shadow': 'error',
            '@typescript-eslint/consistent-type-imports': 'error',
            '@typescript-eslint/no-unused-vars': ['error', {
                argsIgnorePattern: '^_',
                varsIgnorePattern: '^_',
                caughtErrorsIgnorePattern: '^_|^(e|err)$',
            }],
            '@typescript-eslint/no-namespace': 'off',
            '@typescript-eslint/restrict-template-expressions': 'off',
            /* typescript rules */

            'sort-imports': ['error', {
                'ignoreCase': false,
                'ignoreDeclarationSort': false,
                'ignoreMemberSort': false,
                'memberSyntaxSortOrder': ['none', 'all', 'multiple', 'single'],
                'allowSeparatedGroups': false
            }],
        },
    },
    {
        files: ['**/*.html.twig'],

        rules: {
            'vue/attribute-hyphenation': 'off', // $attrs doesn't normalize kebab -> camelCase
            'vue/v-on-event-hyphenation': 'off',
            'vue/no-v-html': 'off',
            'vue/no-unused-vars': 'off',
            'vue/valid-v-slot': 'off',
            'vue/require-v-for-key': 'off',
            'vue/valid-v-for': 'off',
            'vue/no-lone-template': 'off',
            'vue/valid-v-model': 'off',

            // templates are all wrong formatted
            'vue/first-attribute-linebreak': 'off',
            'vue/html-closing-bracket-newline': 'off',
        },
    },
    {
        files: ['**/*.js'],

        extends: [tseslint.configs.disableTypeChecked],
    },
);