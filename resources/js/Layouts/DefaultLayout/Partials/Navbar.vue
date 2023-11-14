<script setup>
import {
    Avatar,
    DropdownHeader,
    DropdownItem,
    DropdownLabel,
    DropdownMenu,
    DropdownSeparator,
    LanguageSelector,
    Switch,
} from '@/Components/UI';

import { Link } from '@inertiajs/vue3';
import { ref, watch, onMounted } from 'vue';
import Login from './Login.vue';
import {
    ArrowRightOnRectangleIcon,
    Cog8ToothIcon,
    UserIcon,
    BuildingOffice2Icon,
} from '@heroicons/vue/24/outline';
import { useLocaleStore } from '@/Stores/localeStore';
import { useCookieStore } from '@/Stores/cookieStore';
import {
    Cog6ToothIcon,
    MoonIcon,
    SunIcon,
    BellIcon,
} from '@heroicons/vue/24/solid';
import { languages } from '@/Languages/languages';
import { useCurrentUser } from '@/Composables/useCurrentUser';

const localeStore = useLocaleStore();
const cookieStore = useCookieStore();
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
                    align="right"
                    class="mt-6 w-[20em] dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100"
                >
                    <template v-slot:dropdownMenuButton>
                        <BellIcon class="h-6 w-6 dark:text-white" />
                    </template>
                    <DropdownHeader>{{
                        $t('sections.notifications')
                    }}</DropdownHeader>
                </DropdownMenu>

                <!-- Settings Panel -->
                <DropdownMenu
                    align="right"
                    class="mt-6 w-[10em] dark:border-zinc-600 dark:bg-zinc-800"
                >
                    <template v-slot:dropdownMenuButton>
                        <Cog6ToothIcon class="h-6 w-6 dark:text-white" />
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
                        <SunIcon class="w-6 text-yellow-500" />
                        <Switch
                            :value="cookieStore.theme === 'dark'"
                            :on-change="
                                (value) => {
                                    cookieStore.setDarkMode(
                                        value ? 'dark' : 'light',
                                    );
                                }
                            "
                        /><MoonIcon
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
                                :leftIcon="UserIcon"
                                >{{ $t('sections.profile') }}</DropdownItem
                            >
                            <DropdownItem
                                v-if="currentUser.isOwner"
                                :is="Link"
                                :href="route('companies.index')"
                                class="text-sm font-bold dark:hover:bg-zinc-700"
                                :leftIcon="BuildingOffice2Icon"
                                >{{
                                    $tc('labels.business.self', 2)
                                }}</DropdownItem
                            >
                            <DropdownItem
                                v-else
                                :is="Link"
                                :href="route('companies.create')"
                                class="text-sm font-bold dark:hover:bg-zinc-700"
                                :leftIcon="BuildingOffice2Icon"
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
                                :leftIcon="Cog8ToothIcon"
                                >{{
                                    $t('sections.settings.self')
                                }}</DropdownItem
                            >
                            <DropdownItem
                                :is="Link"
                                :href="route('logout')"
                                method="POST"
                                class="text-sm font-bold dark:hover:bg-zinc-700"
                                :leftIcon="ArrowRightOnRectangleIcon"
                                >{{ $t('common.logout') }}</DropdownItem
                            >
                        </div>
                    </div>
                </DropdownMenu>
            </div>
        </div>
    </header>
</template>
