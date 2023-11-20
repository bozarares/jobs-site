// localeStore.js

import { loadLanguageAsync } from '@/Languages/i18n';
import { defineStore } from 'pinia';

export const useLocaleStore = defineStore('locale', {
    state: () => ({
        locale: 'en',
        profileLocale: 'en',
    }),
    actions: {
        async setLocale(newLocale) {
            await loadLanguageAsync(newLocale);
            this.setProfileLocale(newLocale);
            this.locale = newLocale;
        },
        setProfileLocale(newLocale) {
            this.profileLocale = newLocale;
        },
    },
});
