<script setup>
import {
    Avatar,
    DropdownHeader,
    DropdownItem,
    DropdownLabel,
    DropdownMenu,
    DropdownSeparator,
    LanguageSelector,
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

const localeStore = useLocaleStore();

const language = ref(localeStore.locale);
const page = usePage();
const isOwner = computed(() => {
    if (page.props.auth.user) {
        return page.props.auth.user.isOwner;
    }
    return false;
});

watch(
    () => language.value,
    (newValue) => {
        localeStore.setLocale(newValue);
    },
);

watch(
    () => page.props.auth.user,
    (newValue, oldValue) => {
        if (newValue && !oldValue) {
            broadcastListen(newValue.id);
            console.log('Listening to user ' + newValue.id);
        } else if (!newValue && oldValue) {
            broadcastDisconnect();
            console.log('Disconnecting from user ' + oldValue.id);
        }
    },
    { deep: true },
);
</script>

<template>
    <header class="sticky z-50 items-center bg-white py-2 shadow">
        <div
            class="container mx-auto flex max-w-screen-lg justify-between px-4"
        >
            <Link href="/">
                <img
                    class="h-8 fill-current object-contain text-gray-500"
                    src="/images/logo/logo-black.png"
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
                    >{{ $t('buttons.businesses') }}</Button
                >
                <Link
                    v-else
                    :href="route('companies.create')"
                    class="text-sm"
                    >{{ $t('buttons.try_recruting') }}</Link
                >
                <DropdownMenu align="right" class="mt-6 w-[20em]">
                    <template v-slot:dropdownMenuButton>
                        <BellIcon class="h-6 w-6" />
                    </template>
                    <DropdownHeader>{{
                        $t('labels.notifications')
                    }}</DropdownHeader>
                </DropdownMenu>
                <LanguageSelector
                    v-model="language"
                    :languages="[
                        { locale: 'en_US', emoji: '🇺🇸', name: 'English' },
                        { locale: 'ro_RO', emoji: '🇷🇴', name: 'Română' },
                        { locale: 'ja_JP', emoji: '🇯🇵', name: '日本語' },
                        // { locale: 'fr', emoji: '🇫🇷', name: 'Francais' },
                    ]"
                />
                <DropdownMenu align="right" class="mt-4 w-[20em]">
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
                                    <p class="text-xl font-bold text-black">
                                        {{ $page.props.auth.user.name }}
                                    </p>
                                    <p class="text-sm font-bold text-black/50">
                                        {{ $page.props.auth.user.email }}
                                    </p>
                                </div>
                            </div>
                        </DropdownHeader>
                        <div class="flex flex-col">
                            <DropdownSeparator />
                            <DropdownItem
                                :is="Link"
                                :href="route('profile.show')"
                                class="text-sm font-bold"
                                :leftIcon="UserIcon"
                                >{{ $t('labels.profile') }}</DropdownItem
                            >
                            <DropdownSeparator />
                            <DropdownLabel align="left">{{
                                $t('labels.user_controlls')
                            }}</DropdownLabel>
                            <DropdownItem
                                class="text-sm font-bold"
                                :leftIcon="Cog8ToothIcon"
                                >{{ $t('labels.settings') }}</DropdownItem
                            >
                            <DropdownItem
                                :is="Link"
                                :href="route('logout')"
                                method="POST"
                                class="text-sm font-bold"
                                :leftIcon="ArrowRightOnRectangleIcon"
                                >{{ $t('labels.logout') }}</DropdownItem
                            >
                        </div>
                    </div>
                </DropdownMenu>
            </div>
        </div>
    </header>
</template>
