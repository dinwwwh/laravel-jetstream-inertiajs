module.exports = {
    env: {
        browser: true,
        es2021: true,
    },
    extends: [
        'plugin:vue/vue3-recommended',
        'airbnb-base',
        'prettier',
        'plugin:tailwindcss/recommended',
    ],
    parserOptions: {
        ecmaVersion: 13,
        parser: '@typescript-eslint/parser',
        sourceType: 'module',
    },
    plugins: ['vue', '@typescript-eslint', 'tailwindcss'],
    rules: {
        'import/extensions': 'off',
        'import/no-unresolved': 'off',
    },
    overrides: [
        {
            files: ['**.ts', '**.vue'],
            // excludedFiles: '*.d.ts',
            rules: {
                'no-undef': 'off',
            },
        },
        {
            files: ['**.d.ts'],
            rules: {
                'no-use-before-define': 'off',
                'no-unused-vars': 'off',
                'no-shadow': 'off',
            },
        },
    ],
};
