<script setup>
import { Button, Switch } from '@/Components/UI';
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
            <h2 class="text-lg font-bold uppercase text-black/60">
                {{ $t('fields.settings') }}
            </h2>
            <h2 class="text-sm font-bold text-black/70">
                {{ $tc('labels.applicants', job.application_count) }}
            </h2>
            <Button
                v-if="job.application_count > 0"
                @click="modalStore.openModal('jobApplications')"
                class="w-full"
                :options="{ shape: 'pill', color: 'green' }"
            >
                {{ $tc('buttons.view_applicants', job.application_count) }}
            </Button>
            <div class="flex items-center gap-2">
                <h2>{{ $t('generic.edit_job') }}</h2>
                <Switch
                    :on-change="
                        (value) => {
                            edit = value;
                            toggleEdit(edit);
                        }
                    "
                />
            </div>
            <Button
                @click="modalStore.openModal('jobDelete')"
                class="w-full"
                :options="{ shape: 'pill', color: 'red' }"
                >{{ $t('buttons.delete') }}</Button
            >
        </div>
    </div>
</template>
