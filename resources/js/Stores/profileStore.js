// localeStore.js

import { defineStore } from 'pinia';

export const useProfileStore = defineStore('profile', {
    state: () => ({
        profileWatcherLocale: null,
        profileLanguage: 'en',
        description: null,
        jobHistory: null,
        educationHistory: null,
    }),
    actions: {
        setData(data) {
            this.description = data.description;
            this.jobHistory = data.jobHistory;
            this.educationHistory = data.educationHistory;
            this.profileLanguage = data.language;
        },
        setProfileWatcher(newLocale) {
            this.profileWatcherLocale = newLocale;
        },
        resetProfileWatcher() {
            this.profileWatcherLocale = null;
        },
    },
});
