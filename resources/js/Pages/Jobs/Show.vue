<script setup>
import { Head, usePage } from '@inertiajs/vue3';
import JobCard from './Partials/JobCard.vue';
import SettingsCard from './Partials/SettingsCard.vue';
import ContentCard from '@/Components/ContentCard.vue';
import Job from '@/Models/Job';

const props = defineProps({
    applied: {
        type: Boolean,
        default: false,
    },
    job: {
        type: Object,
        default: () => {},
    },
    is_owner: {
        type: Boolean,
        default: false,
    },
});
const jobInstance = computed(() => {
    return new Job(props.job);
});
const edit = ref(false);
</script>

<template>
    <Head :title="jobInstance.title" />

    <div
        class="mt-12 flex w-full max-w-screen-lg flex-wrap justify-center gap-8 p-6 md:flex-nowrap"
    >
        <div class="flex w-full flex-col items-center gap-4 md:w-auto">
            <JobCard
                :alreadyApplied="props.applied"
                :job="jobInstance"
                :edit="edit"
            />
            <SettingsCard
                :job="jobInstance"
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
                    v-html="jobInstance.description"
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
                        v-for="skill in jobInstance.skills"
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
