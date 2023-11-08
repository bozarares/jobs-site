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
import Button from '@/Components/UI/Button/Button.vue';

import { Link, usePage } from '@inertiajs/vue3';
import { ref, watch, computed } from 'vue';
import Login from './Login.vue';
import { broadcastDisconnect, broadcastListen } from '@/broadcast';
import {
    ArrowRightOnRectangleIcon,
    BellIcon,
    Cog8ToothIcon,
    UserIcon,
} from '@heroicons/vue/24/outline';
import { useLocaleStore } from '@/Stores/localeStore';
import { useCookieStore } from '@/Stores/cookieStore';
import { Cog6ToothIcon, MoonIcon, SunIcon } from '@heroicons/vue/24/solid';
import { languages } from '@/Languages/languages';
import axios from 'axios';
import { onMounted } from 'vue';

const localeStore = useLocaleStore();
const cookieStore = useCookieStore();
const language = ref(localeStore.locale);
const page = usePage();
const isOwner = computed(() => {
    if (page.props.auth.user) {
        return page.props.auth.user.isOwner;
    }
    return false;
});

function getCookie(cname) {
    let name = cname + '=';
    let decodedCookie = decodeURIComponent(document.cookie);
    let ca = decodedCookie.split(';');
    for (let i = 0; i < ca.length; i++) {
        let c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return '';
}

watch(
    () => language.value,
    (newValue) => {
        localeStore.setLocale(newValue);
        localeStore.setProfileLocale(newValue);

        if (page.props.auth.user) {
            page.props.auth.user.locale = newValue;
            axios.post(route('language'), { language: newValue });
        } else {
            if (
                typeof getCookie('user_locale') === 'undefined' ||
                getCookie('user_locale') !== newValue
            ) {
                document.cookie = `user_locale=${newValue};path=/;max-age=${
                    60 * 24 * 30 * 24 * 60 * 60
                }`;
            }
        }
    },
);

watch(
    () => page.props.auth.user,
    (newValue, oldValue) => {
        if (newValue && !oldValue) {
            const userLocale = newValue.locale || 'en';
            localeStore.setLocale(userLocale);
            broadcastListen(newValue.id);
            console.log('Listening to user ' + newValue.id);
        } else if (!newValue && oldValue) {
            broadcastDisconnect();
            console.log('Disconnecting from user ' + oldValue.id);
        }
    },
    { deep: true },
);

onMounted(() => {
    const cookieValue = getCookie('user_locale');
    if (cookieValue) {
        localeStore.setLocale(cookieValue);
        language.value = cookieValue;
    } else {
        localeStore.setLocale('en');
        language.value = 'en';
    }

    if (page.props.auth.user === null) {
        broadcastDisconnect();
    }
});
</script>

<template>
    <header
        class="sticky z-50 items-center bg-white py-2 shadow dark:bg-zinc-800"
    >
        <div
            class="container mx-auto flex max-w-screen-lg justify-between px-4"
        >
            <Link href="/">
                <img
                    class="h-8 fill-current object-contain text-gray-500"
                    :src="
                        cookieStore.theme === 'dark'
                            ? '/images/logo/logo-white.png'
                            : '/images/logo/logo-black.png'
                    "
                    alt="Logo"
                />
            </Link>
            <div class="flex items-center gap-6">
                <Button
                    as="a"
                    :is="Link"
                    :href="route('companies.index')"
                    v-if="isOwner"
                    :options="{ shape: 'pill', color: 'green' }"
                    >{{ $tc('labels.business.self', 2) }}</Button
                >
                <Link
                    v-else
                    :href="route('companies.create')"
                    class="text-sm dark:text-white"
                    >{{ $t('sections.recruit') }}</Link
                >

                <!-- Notification Panel -->
                <DropdownMenu align="right" class="mt-6 w-[20em]">
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
                    class="mt-6 w-[10em] dark:bg-zinc-800"
                >
                    <template v-slot:dropdownMenuButton>
                        <Cog6ToothIcon class="h-6 w-6 dark:text-white" />
                    </template>
                    <DropdownHeader class="dark:text-gray-100">{{
                        $t('sections.settings')
                    }}</DropdownHeader>

                    <LanguageSelector
                        class="dark:text-gray-100"
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
                    class="mt-4 w-[20em] dark:border-zinc-600 dark:bg-zinc-800 dark:text-gray-100"
                >
                    <template v-slot:dropdownMenuButton>
                        <Avatar
                            :src="
                                $page.props.auth.user &&
                                $page.props.auth.user.avatar
                                    ? '/users/avatars/' +
                                      $page.props.auth.user.avatar +
                                      '.' +
                                      $page.props.auth.user.avatar_extension
                                    : null
                            "
                            id="user-toggle"
                            size="small"
                            :name="
                                $page.props.auth.user
                                    ? $page.props.auth.user.name
                                    : null
                            "
                        />
                    </template>
                    <Login v-if="!$page.props.auth.user" />
                    <div
                        v-else
                        id="navbar-user"
                        class="flex w-full flex-col gap-4 p-4"
                    >
                        <DropdownHeader>
                            <div class="flex items-center justify-between">
                                <Avatar
                                    :src="
                                        $page.props.auth.user &&
                                        $page.props.auth.user.avatar
                                            ? '/users/avatars/' +
                                              $page.props.auth.user.avatar +
                                              '.' +
                                              $page.props.auth.user
                                                  .avatar_extension
                                            : null
                                    "
                                    id="user-toggle"
                                    size="big"
                                    :name="
                                        $page.props.auth.user
                                            ? $page.props.auth.user.name
                                            : null
                                    "
                                />
                                <div class="flex flex-col items-center">
                                    <p
                                        class="text-xl font-bold text-black dark:text-gray-200"
                                    >
                                        {{ $page.props.auth.user.name }}
                                    </p>
                                    <p
                                        class="text-sm font-bold text-black/50 dark:text-gray-200"
                                    >
                                        {{ $page.props.auth.user.email }}
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
                            <DropdownSeparator class="dark:bg-zinc-700" />
                            <DropdownLabel
                                align="left"
                                class="dark:text-zinc-700"
                                >{{ $t('labels.userControlls') }}</DropdownLabel
                            >
                            <DropdownItem
                                class="text-sm font-bold dark:hover:bg-zinc-700"
                                :leftIcon="Cog8ToothIcon"
                                >{{ $t('sections.settings') }}</DropdownItem
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
