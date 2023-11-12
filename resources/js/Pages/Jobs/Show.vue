<script setup>
import { Head, usePage } from '@inertiajs/vue3';
import JobCard from './Partials/JobCard.vue';
import { ref } from 'vue';
import SettingsCard from './Partials/SettingsCard.vue';
import { useModalStore } from '@/Stores/modalStore';
import ContentCard from '@/Components/ContentCard.vue';

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
    <Head :title="job.title" />

    <div
        class="mt-12 flex w-full max-w-screen-lg flex-wrap justify-center gap-8 p-6 md:flex-nowrap"
    >
        <div class="flex w-full flex-col items-center gap-4 md:w-auto">
            <JobCard :alreadyApplied="props.applied" :job="job" :edit="edit" />
            <SettingsCard
                :job="job"
                :toggle-edit="
                    (value) => {
                        edit = value;
                    }
                "
            />
        </div>
        <div
            class="flex w-full flex-grow flex-col items-center justify-start gap-4 md:w-3/4"
        >
            <!-- Description -->
            <ContentCard
                :title="$t('labels.description.self')"
                :edit="edit"
                id-edit="job-description-edit"
                modal="jobDescription"
            >
                <div
                    class="ql-editor prose dark:!text-zinc-100"
                    v-html="job.description"
                />
            </ContentCard>

            <!-- Requirements -->
            <ContentCard
                :title="$t('labels.skills.required')"
                :edit="edit"
                id-edit="job-skills-edit"
                modal="jobSkills"
            >
                <div class="flex flex-wrap gap-2">
                    <div
                        v-for="skill in job.skills"
                        :key="skill.id"
                        class="select-none rounded-full px-3 py-1 outline outline-zinc-400 transition-all duration-150 ease-in-out hover:scale-105"
                    >
                        {{ skill.name }}
                    </div>
                </div>
            </ContentCard>
        </div>
    </div>
</template>
