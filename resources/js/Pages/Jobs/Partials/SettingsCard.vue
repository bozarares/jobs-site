<script setup>
import { useModalStore } from '@/Stores/modalStore';

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
        class="group container relative flex w-full flex-col justify-between overflow-hidden rounded bg-white p-6 shadow-md dark:bg-zinc-800 dark:text-zinc-100 md:w-[15em]"
    >
        <div class="relative flex flex-col items-center gap-2">
            <h2 class="text-lg font-bold uppercase">
                {{ $t('sections.settings.self') }}
            </h2>
            <!-- <h2 class="text-sm font-bold text-black/70">
                {{ $tc('labels.applicants', job.application_count) }}
            </h2> -->
            <Button
                v-if="job.application_count > 0"
                @click="modalStore.openModal('jobApplications')"
                class="w-full"
                :options="{ shape: 'pill', color: 'blue' }"
            >
                {{ $tc('labels.applications', job.application_count) }}
            </Button>
            <div class="flex items-center gap-2">
                <h2>{{ $t('actions.editJob') }}</h2>
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
                >{{ $t('common.delete') }}</Button
            >
        </div>
    </div>
</template>
