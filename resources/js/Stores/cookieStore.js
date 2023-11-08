// cookieStore.js
import { getCookie, setCookie, eraseCookie } from '@/cookies';
import { usePage } from '@inertiajs/vue3';
import axios from 'axios';
import { defineStore } from 'pinia';

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
    },
});
