// useUpdateUserData.js

import { useLocaleStore } from '@/Stores/localeStore';
import { useProfileStore } from '@/Stores/profileStore';
import { ref } from 'vue';

export function useUpdateUserData(user) {
    const isLoading = ref(false);
    const error = ref(null);
    const localeStore = useLocaleStore();
    const profileStore = useProfileStore();

    const updateDescription = async (newDescription) => {
        if (!user || !user.isSet()) return;
        const locale = localeStore.profileLocale;
        await axios.post(route('profile.update.description'), {
            description: newDescription,
            locale,
        });
        profileStore.setProfileWatcher(locale);
    };

    return {
        isLoading,
        error,
        updateDescription,
    };
}
