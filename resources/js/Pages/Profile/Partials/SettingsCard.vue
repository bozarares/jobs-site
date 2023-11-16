<script setup>
import { Switch } from '@/Components/UI';

const props = defineProps({
    toggleEdit: {
        type: Function,
        default: () => {},
    },
    user: {
        type: Object,
        required: true,
    },
});

const edit = ref(false);
</script>

<template>
    <div
        class="group container relative flex w-full flex-col justify-between rounded bg-white p-6 text-zinc-600 shadow-md dark:bg-zinc-800 dark:text-zinc-100 md:w-[15em]"
    >
        <div class="relative flex flex-col items-center gap-2">
            <h2 class="text-lg font-bold uppercase">
                {{ $t('sections.settings.self') }}
            </h2>
            <h2
                :class="{
                    'bg-red-500': user.profileCompletion < 50,
                    'bg-yellow-500':
                        user.profileCompletion >= 50 &&
                        user.profileCompletion < 75,
                    'bg-green-500': user.profileCompletion >= 75,
                }"
                class="rounded px-2 py-0.5 font-extrabold text-white"
            >
                {{ $t('sections.profile') }}:
                <span>{{ Math.round(user.profileCompletion) }}%</span>
            </h2>
            <div class="flex items-center gap-2">
                <h2>{{ $t('actions.editProfile') }}</h2>
                <Switch
                    :on-change="
                        (value) => {
                            edit = value;
                            toggleEdit(edit);
                        }
                    "
                />
            </div>
        </div>
    </div>
</template>
