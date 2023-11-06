<script setup>
import { Switch } from '@/Components/UI';
import { watch } from 'vue';
import { ref } from 'vue';

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
        class="group container relative flex w-full flex-col justify-between overflow-hidden rounded bg-white p-6 shadow-md md:w-[15em]"
    >
        <div class="relative flex flex-col items-center gap-2">
            <h2 class="text-lg font-bold uppercase text-black/60">Settings</h2>
            <h2>
                Profile Completion:
                <span>{{ Math.round(user.profileCompletion) }}%</span>
            </h2>
            <div class="flex items-center gap-2">
                <h2>Edit Profile</h2>
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
