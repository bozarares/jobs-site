<script setup>
import { usePage } from '@inertiajs/vue3';
import JobCard from './Partials/JobCard.vue';
import { ref } from 'vue';
import SettingsCard from './Partials/SettingsCard.vue';
import { useModalStore } from '@/Stores/modalStore';

const modalStore = useModalStore();

const props = defineProps({
    applied: {
        type: Boolean,
        default: false,
    },
    job: {
        type: Object,
        default: () => {},
    },
});

const page = usePage();
const edit = ref(false);
</script>

<template>
    <div
        class="mt-12 flex w-full max-w-screen-lg flex-wrap justify-center gap-8 p-6 md:flex-nowrap"
    >
        <div class="flex w-full flex-col gap-4 md:w-auto">
            <JobCard :alreadyApplied="props.applied" :job="job" :edit="edit" />
            <SettingsCard
                :toggle-edit="
                    (value) => {
                        edit = value;
                    }
                "
            />
        </div>
        <div
            class="flex w-full flex-grow flex-col justify-start gap-4 md:w-3/4"
        >
            <div
                :id="edit ? 'company-description-edit' : ''"
                class="group container relative flex w-full flex-col gap-4 rounded bg-white p-6 shadow-md"
                :class="{
                    'cursor-pointer': edit,
                }"
                @click="
                    () => {
                        if (edit) {
                            modalStore.openModal('jobDescription');
                        }
                    }
                "
            >
                <h2
                    v-if="edit"
                    class="absolute right-0 top-0 pr-2 font-extrabold text-gray-500 transition-all duration-150 ease-in-out group-hover:text-black"
                >
                    Click field to edit
                </h2>
                <h2 class="text-lg font-bold uppercase text-black/60">
                    Description
                </h2>
                <div class="ql-editor prose" v-html="job.description"></div>
            </div>
            <div
                :id="edit ? 'company-description-edit' : ''"
                class="group container relative flex w-full flex-col gap-4 rounded bg-white p-6 shadow-md"
                :class="{
                    'cursor-pointer': edit,
                }"
                @click="
                    () => {
                        if (edit) {
                            modalStore.openModal('jobSkills');
                        }
                    }
                "
            >
                <h2
                    v-if="edit"
                    class="absolute right-0 top-0 pr-2 font-extrabold text-gray-500 transition-all duration-150 ease-in-out group-hover:text-black"
                >
                    Click field to edit
                </h2>
                <h2 class="text-lg font-bold uppercase text-black/60">
                    Required Skills
                </h2>
                <div class="flex flex-wrap gap-2">
                    <div
                        v-for="skill in job.skills"
                        :key="skill.id"
                        class="select-none rounded-full px-3 py-1 outline outline-gray-400 transition-all duration-150 ease-in-out hover:scale-105"
                    >
                        {{ skill.name }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
