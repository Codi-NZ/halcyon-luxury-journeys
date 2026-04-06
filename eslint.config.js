import js from '@eslint/js'
import globals from 'globals'

export default [
    js.configs.recommended,
    {
        languageOptions: {
            ecmaVersion: 'latest',
            sourceType: 'module',
            globals: {
                ...globals.browser,
                Alpine: 'readonly',
                google: 'readonly',
            },
        },
        rules: {
            // Enforce strict equality
            eqeqeq: ['error', 'always'],

            // No console in production (warn to allow during dev)
            'no-console': ['warn', { allow: ['warn', 'error'] }],

            // Require const/let over var
            'no-var': 'error',
            'prefer-const': 'error',

            // Code style
            'no-unused-vars': ['error', { argsIgnorePattern: '^_', varsIgnorePattern: '^_' }],
            'no-multiple-empty-lines': ['error', { max: 1 }],

            // Best practices
            'no-implicit-coercion': 'error',
            'no-return-await': 'error',
            'require-await': 'error',
        },
    },
    {
        // Config files can use CommonJS and console
        files: ['*.config.js', 'svg.js'],
        languageOptions: {
            globals: {
                ...globals.node,
            },
        },
        rules: {
            'no-console': 'off',
        },
    },
    {
        ignores: [
            'node_modules/',
            'vendor/',
            'web/dist/',
            'web/cpresources/',
            'src/sprite.svg',
        ],
    },
]
