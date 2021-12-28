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
    rules: {},
    settings: {
        'import/resolver': {
            alias: {
                map: [['@', './resources/js']],
            },
        },
    },
};
