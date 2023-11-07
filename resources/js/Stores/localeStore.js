// localeStore.js

import { loadLanguageAsync } from '@/Languages/i18n';
import { defineStore } from 'pinia';

export const useLocaleStore = defineStore('locale', {
    state: () => ({
        locale: 'en',
    }),
    actions: {
        async setLocale(newLocale) {
            await loadLanguageAsync(newLocale);
            this.locale = newLocale;
        },
    },
});
