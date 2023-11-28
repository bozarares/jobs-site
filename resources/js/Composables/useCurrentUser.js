import User from '@/Models/User';
import { useCookieStore } from '@/Stores/cookieStore';
import { useLocaleStore } from '@/Stores/localeStore';
import { useUserStore } from '@/Stores/userStore';
import { usePage } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

export function useCurrentUser() {
    const page = usePage();
    const localeStore = useLocaleStore();
    const cookieStore = useCookieStore();
    const userStore = useUserStore();
    if (!page.props.auth) return null;
    const currentUser = ref(new User(page.props.auth.user));
    userStore.initializeUser(page.props.auth.user);

    watch(
        () => page.props.auth,
        (newValue, oldvalue) => {
            if (newValue.user === null) {
                userStore.clearUser();
            } else {
                localeStore.setLocale(newValue.user.preferences.locale || 'en');
                cookieStore.theme = newValue.user.preferences.theme || 'light';
                userStore.initializeUser(newValue.user);
            }
            // Todo: Doesn't work; need to figure out why
            currentUser.value = userStore.currentUser;
        },
        { deep: true },
    );

    return currentUser;
}
