<script setup>
import {
    Avatar,
    Checkbox,
    DropdownHeader,
    DropdownMenu,
} from '@/Components/UI';
import Button from '@/Components/UI/Button/Button.vue';

import { Link, usePage } from '@inertiajs/vue3';
import { watch } from 'vue';
import Login from './Login.vue';
import { broadcastDisconnect, broadcastListen } from '@/broadcast';
import { BellIcon } from '@heroicons/vue/24/outline';
import { computed } from 'vue';

const page = usePage();
const isOwner = computed(() => {
    if (page.props.auth.user) {
        return page.props.auth.user.isOwner;
    }
    return false;
});
// If you want to use watch
watch(
    () => page.props.auth.user,
    (newValue, oldValue) => {
        // Dacă un utilizator nou este autentificat și vechiul utilizator nu a fost autentificat
        if (newValue && !oldValue) {
            broadcastListen(newValue.id);
            console.log('Listening to user ' + newValue.id);
        }
        // Dacă nu există niciun utilizator autentificat și anterior a fost un utilizator autentificat
        else if (!newValue && oldValue) {
            broadcastDisconnect();
            console.log('Disconnecting from user ' + oldValue.id);
        }
    },
    { deep: true }, // Pentru a urmări schimbările adânci în proprietatea user
);
</script>

<template>
    <header class="sticky z-50 py-2 items-center shadow bg-white">
        <div
            class="container flex justify-between mx-auto px-4 max-w-screen-lg"
        >
            <Link href="/">
                <img
                    class="h-8 fill-current object-contain text-gray-500"
                    src="/images/logo/logo-black.png"
                    alt="Logo"
                />
            </Link>
            <div class="flex gap-6 items-center">
                <Button
                    as="a"
                    :is="Link"
                    :href="route('companies.index')"
                    v-if="isOwner"
                    :options="{ shape: 'pill', color: 'green' }"
                    >Businesses</Button
                >
                <Link v-else :href="route('companies.create')" class="text-sm"
                    >Try recruiting</Link
                >
                <DropdownMenu align="right" class="w-[20em] mt-6">
                    <template v-slot:dropdownMenuButton>
                        <BellIcon class="h-6 w-6" />
                    </template>
                    <DropdownHeader>Notification Centre</DropdownHeader>
                </DropdownMenu>
                <DropdownMenu align="right" class="w-[20em] mt-4">
                    <template v-slot:dropdownMenuButton>
                        <Avatar
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
                        class="flex flex-col gap-4 p-4 w-full"
                    >
                        <DropdownHeader>
                            {{ $page.props.auth.user.name }}
                        </DropdownHeader>
                        <Button
                            class="w-full"
                            method="post"
                            :href="route('logout')"
                            as="button"
                            :is="Link"
                        >
                            Logout
                        </Button>
                    </div>
                </DropdownMenu>
            </div>
        </div>
    </header>
</template>
