// cookieStore.js
import { useCurrentUser } from '@/Composables/useCurrentUser';
import { getCookie, setCookie } from '@/cookies';
import { usePage } from '@inertiajs/vue3';
import axios from 'axios';
import { defineStore } from 'pinia';
import { useLocaleStore } from './localeStore';

export const useCookieStore = defineStore('cookie', {
    state: () => ({
        theme: getCookie('theme') || 'light',
        locale: getCookie('user_locale') || 'en',
    }),
    actions: {
        async setDarkMode(newMode) {
            const page = usePage();
            const user = page.props.auth.user;
            if (user !== null) {
                await axios.post(route('changeTheme'), { theme: newMode });
            } else {
                setCookie('theme', newMode, 365);
            }
            this.theme = newMode;
        },

        async setLocale(newLocale) {
            const currentUser = useCurrentUser();
            const localeStore = useLocaleStore();
            if (currentUser.value.isSet())
                await axios.post(route('language'), { language: newLocale });
            else setCookie('user_locale', newLocale, 365);
            this.locale = newLocale;
            localeStore.setLocale(newLocale);
        },
    },
});
