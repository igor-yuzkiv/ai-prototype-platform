import js from '@eslint/js'
import pluginVue from 'eslint-plugin-vue'
import tseslint from 'typescript-eslint'

export default [
    {
        ignores: [
            '**/dist/**',
            '_tmp/**',
            'bootstrap/cache/**',
            'docker/pg-data/**',
            'public/build/**',
            'storage/**',
            'vendor/**',
            '**/vite.config.*.timestamp*',
            '**/vitest.config.*.timestamp*',
        ],
    },
    js.configs.recommended,
    ...tseslint.configs.recommended,
    ...pluginVue.configs['flat/essential'],
    {
        files: ['resources/js/**/*.{js,ts,vue}', 'vite.config.ts', 'eslint.config.js'],
        languageOptions: {
            parserOptions: {
                parser: tseslint.parser,
            },
        },
    },
]
