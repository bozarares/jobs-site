<script setup>
import { Link } from '@inertiajs/vue3';
import Login from './Login.vue';
import mdiLogout from '~icons/mdi/logout';
import mdiAccount from '~icons/mdi/account';
import mdiCog from '~icons/mdi/cog';
import mdiOfficeBuilding from '~icons/mdi/office-building';

import { useLocaleStore } from '@/Stores/localeStore';
import { useCookieStore } from '@/Stores/cookieStore';
import { languages } from '@/Languages/languages';
import { useCurrentUser } from '@/Composables/useCurrentUser';
import { useUserStore } from '@/Stores/userStore';

const localeStore = useLocaleStore();
const cookieStore = useCookieStore();
const userStore = useUserStore();
const currentUser = useCurrentUser();

const language = ref(localeStore.locale);

watch(
    () => language.value,
    (newValue) => {
        cookieStore.setLocale(newValue);
    },
);

watch(
    () => localeStore.locale,
    (newValue) => {
        language.value = newValue;
    },
);

const loadNotifications = (type) => {
    let page = userStore.currentUser.notifications.page;
    if (type === 'prev') {
        if (page === 1) return;
        userStore.currentUser.initializeNotifications(page - 1);
    } else if (type === 'next') {
        if (page === userStore.currentUser.notifications.lastPage) return;
        userStore.currentUser.initializeNotifications(page + 1);
    }
};

onMounted(() => {
    const cookieValue = cookieStore.locale;
    language.value = cookieValue;
});
</script>

<template>
    <header
        class="fixed z-50 w-full items-center bg-white py-2 shadow dark:bg-zinc-800 dark:shadow-zinc-700"
    >
        <div
            class="container mx-auto flex max-w-screen-lg items-center justify-between px-4"
        >
            <Link href="/">
                <img
                    class="h-8 fill-current object-contain text-zinc-500"
                    :src="
                        cookieStore.theme === 'dark'
                            ? '/images/logo/logo-white.png'
                            : '/images/logo/logo-black.png'
                    "
                    alt="Logo"
                />
            </Link>
            <div class="flex items-center gap-6">
                <!-- <Button
                    as="a"
                    :is="Link"
                    :href="route('companies.index')"
                    v-if="isOwner"
                    :options="{ shape: 'pill', color: 'blue' }"
                    >{{ $tc('labels.business.self', 2) }}</Button
                >
                <Link
                    v-else
                    :href="route('companies.create')"
                    class="text-sm dark:text-white"
                    >{{ $t('sections.recruit') }}</Link
                > -->

                <!-- Notification Panel -->
                <DropdownMenu
                    v-if="currentUser && currentUser.isSet()"
                    align="right"
                    class="mt-6 w-[20em] dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100"
                >
                    <template v-slot:dropdownMenuButton>
                        <Mdi:bell class="h-6 w-6 dark:text-white" />
                    </template>
                    <DropdownHeader>{{
                        $t('sections.notifications')
                    }}</DropdownHeader>
                    <div
                        v-for="notification in userStore.currentUser
                            .notifications.data"
                        :key="notification.id"
                    >
                        {{ notification.message }}
                    </div>
                    <div>
                        <Button
                            @click="
                                () => {
                                    loadNotifications('prev');
                                }
                            "
                            >Prev</Button
                        >
                        <Button
                            @click="
                                () => {
                                    loadNotifications('next');
                                }
                            "
                            >Next</Button
                        >
                    </div>
                </DropdownMenu>

                <!-- Settings Panel -->
                <DropdownMenu
                    align="right"
                    class="mt-6 w-[10em] dark:border-zinc-600 dark:bg-zinc-800"
                >
                    <template v-slot:dropdownMenuButton>
                        <Mdi:cog class="h-6 w-6 dark:text-white" />
                    </template>
                    <DropdownHeader class="dark:text-zinc-100">{{
                        $t('sections.settings.self')
                    }}</DropdownHeader>

                    <LanguageSelector
                        class="dark:text-zinc-100"
                        v-model="language"
                        :languages="languages"
                    />
                    <div class="my-5 flex items-center justify-center gap-2">
                        <heroicons:sun-solid class="w-6 text-yellow-500" />
                        <Switch
                            :value="cookieStore.theme === 'dark'"
                            :on-change="
                                (value) => {
                                    cookieStore.setDarkMode(
                                        value ? 'dark' : 'light',
                                    );
                                }
                            "
                        /><heroicons:moon-solid
                            class="w-5 text-indigo-900 dark:text-indigo-500"
                        />
                    </div>
                </DropdownMenu>

                <!-- User Panel -->
                <DropdownMenu
                    align="right"
                    class="mt-4 w-[20em] dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100"
                >
                    <template v-slot:dropdownMenuButton>
                        <Avatar
                            :src="currentUser.avatarPath()"
                            id="user-toggle"
                            size="small"
                            :name="currentUser.name ?? null"
                        />
                    </template>
                    <Login v-if="!currentUser || !currentUser.isSet()" />
                    <div
                        v-else
                        id="navbar-user"
                        class="flex w-full flex-col gap-4 p-4"
                    >
                        <DropdownHeader>
                            <div class="flex items-center justify-between">
                                <Avatar
                                    :src="currentUser.avatarPath()"
                                    id="user-toggle"
                                    size="big"
                                    :name="currentUser.name ?? null"
                                />
                                <div class="flex flex-col items-center">
                                    <p
                                        class="text-xl font-bold text-black dark:text-zinc-200"
                                    >
                                        {{ currentUser.name }}
                                    </p>
                                    <p
                                        class="text-sm font-bold text-black/50 dark:text-zinc-200"
                                    >
                                        {{ currentUser.email }}
                                    </p>
                                </div>
                            </div>
                        </DropdownHeader>
                        <div class="flex flex-col">
                            <DropdownSeparator class="dark:bg-zinc-700" />
                            <DropdownItem
                                :is="Link"
                                :href="route('profile.show')"
                                class="text-sm font-bold dark:hover:bg-zinc-700"
                                :leftIcon="mdiAccount"
                                >{{ $t('sections.profile') }}</DropdownItem
                            >
                            <DropdownItem
                                v-if="currentUser.isOwner"
                                :is="Link"
                                :href="route('companies.index')"
                                class="text-sm font-bold dark:hover:bg-zinc-700"
                                :leftIcon="mdiOfficeBuilding"
                                >{{
                                    $tc('labels.business.self', 2)
                                }}</DropdownItem
                            >
                            <DropdownItem
                                v-else
                                :is="Link"
                                :href="route('companies.create')"
                                class="text-sm font-bold dark:hover:bg-zinc-700"
                                :leftIcon="mdiOfficeBuilding"
                                >{{ $t('sections.recruit') }}</DropdownItem
                            >
                            <DropdownSeparator class="dark:bg-zinc-700" />
                            <DropdownLabel
                                align="left"
                                class="dark:text-zinc-700"
                                >{{ $t('labels.userControlls') }}</DropdownLabel
                            >
                            <DropdownItem
                                :is="Link"
                                :href="route('settings.show')"
                                class="text-sm font-bold dark:hover:bg-zinc-700"
                                :leftIcon="mdiCog"
                                >{{
                                    $t('sections.settings.self')
                                }}</DropdownItem
                            >
                            <DropdownItem
                                :is="Link"
                                :href="route('logout')"
                                method="POST"
                                class="text-sm font-bold dark:hover:bg-zinc-700"
                                :leftIcon="mdiLogout"
                                >{{ $t('common.logout') }}</DropdownItem
                            >
                        </div>
                    </div>
                </DropdownMenu>
            </div>
        </div>
    </header>
</template>
