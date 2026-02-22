import pluginVue from 'eslint-plugin-vue';
import eslintConfigPrettier from 'eslint-config-prettier';

export default [
    // Add Vue 'essential' rules
    ...pluginVue.configs['flat/essential'],
    // Disables ESLint rules that might conflict with prettier
    eslintConfigPrettier,
    {
        rules: {
            // Custom overrides can go here
            'vue/multi-word-component-names': 'off', // Commonly turned off in smaller apps
        }
    }
];
