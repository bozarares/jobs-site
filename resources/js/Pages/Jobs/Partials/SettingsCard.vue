<script setup>
import { Button } from '@/Components/UI';
import { useModalStore } from '@/Stores/modalStore';
import { ref } from 'vue';

const modalStore = useModalStore();

const props = defineProps({
    toggleEdit: {
        type: Function,
        default: () => {},
    },
    job: {
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
            <h2 class="text-sm font-bold text-black/70">
                Applicants:
                {{
                    job.application_count === 0 ? 'none' : job.application_count
                }}
            </h2>
            <Button
                v-if="job.application_count > 0"
                @click="modalStore.openModal('jobApplications')"
                class="w-full"
                :options="{ shape: 'pill', color: 'green' }"
            >
                View Applicants
            </Button>
            <Button
                id="edit-profile-button"
                class="w-full"
                @click="
                    () => {
                        edit = !edit;
                        toggleEdit(edit);
                    }
                "
                :options="{ shape: 'pill', color: 'green' }"
                >{{ edit ? 'Stop edit' : 'Edit' }} Job</Button
            >
            <Button
                @click="modalStore.openModal('jobDelete')"
                class="w-full"
                :options="{ shape: 'pill', color: 'red' }"
                >Delete</Button
            >
        </div>
    </div>
</template>
