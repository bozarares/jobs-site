import User from '@/Models/User';
import { useCookieStore } from '@/Stores/cookieStore';
import { useLocaleStore } from '@/Stores/localeStore';
import { broadcastDisconnect, broadcastListen } from '@/broadcast';
import { usePage } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

export function useCurrentUser() {
    const page = usePage();
    const localeStore = useLocaleStore();
    const cookieStore = useCookieStore();
    if (!page.props.auth) return null;
    const currentUser = ref(new User(page.props.auth.user));

    watch(
        () => page.props.auth,
        (newValue, oldValue) => {
            if (newValue.user === null) {
                currentUser.value = new User();
                broadcastDisconnect();
            } else {
                console.log(newValue.user.preferences.locale);
                localeStore.setLocale(newValue.user.preferences.locale || 'en');
                cookieStore.theme = newValue.user.preferences.theme || 'light';
                broadcastListen(newValue.user.id);
                currentUser.value = new User(newValue.user);
            }
        },
        { deep: true },
    );

    return currentUser;
}
