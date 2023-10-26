<script setup>
import { Button } from '@/Components/UI';
import { ref } from 'vue';

const props = defineProps({
    toggleEdit: {
        type: Function,
        default: () => {},
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
            <Button
                id="edit-profile-button"
                @click="
                    () => {
                        edit = !edit;
                        toggleEdit(edit);
                    }
                "
                :options="{ shape: 'pill', color: 'green' }"
                >{{ edit ? 'Stop edit' : 'Edit' }} Profile</Button
            >
        </div>
    </div>
</template>
