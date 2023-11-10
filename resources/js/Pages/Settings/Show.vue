<script setup>
import PasswordSettings from './Partials/PasswordSettings.vue';
import AccountSettings from './Partials/AccountSettings.vue';
import { Button } from '@/Components/UI';
import { shallowRef } from 'vue';
import { markRaw } from 'vue';
const props = defineProps({
    user: Object,
});
const settings = shallowRef(AccountSettings);
</script>

<template>
    <div
        class="mt-12 flex w-full max-w-screen-lg flex-wrap justify-start gap-2"
    >
        <div
            class="h-max w-[15em] flex-none flex-col rounded bg-white p-4 dark:bg-zinc-800 dark:text-zinc-100"
        >
            <h2 class="text-2xl font-extrabold">Settings</h2>
            <div class="my-4 flex w-full flex-col gap-4">
                <Button
                    @click="
                        () => {
                            settings = AccountSettings;
                        }
                    "
                >
                    {{ $t('sections.settings.account') }}
                </Button>
                <Button
                    @click="
                        () => {
                            settings = markRaw(PasswordSettings);
                        }
                    "
                >
                    {{ $t('sections.settings.privacy') }}
                </Button>
                <Button> Notifications </Button>
            </div>
        </div>

        <!-- Coloana pentru conținut care ocupă restul spațiului -->
        <div
            class="h-max flex-grow rounded bg-white p-4 dark:bg-zinc-800 dark:text-zinc-100"
        >
            <component :is="settings" :user="props.user" />
        </div>
    </div>
</template>
