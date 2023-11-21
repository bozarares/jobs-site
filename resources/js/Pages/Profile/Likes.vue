<script setup>
import { Link } from '@inertiajs/vue3';
import JobApplicationCard from '@/Components/JobApplicationCard.vue';
import Job from '@/Models/Job';

const props = defineProps({
    jobs: {
        type: Array,
    },
});

const jobInstances = computed(() => {
    return props.jobs.map((jobData) => new Job(jobData));
});
</script>

<template>
    <div
        class="flex w-full justify-center gap-4 bg-white/75 py-1 text-sm font-bold text-zinc-800 dark:bg-zinc-800/95 dark:text-zinc-100"
    >
        <Link :href="route('profile.applications')">{{
            $t('sections.applications')
        }}</Link>
        <Link :href="route('profile.likes')">{{ $t('sections.likes') }}</Link>
    </div>

    <div
        class="flex w-full max-w-screen-lg flex-wrap justify-center gap-2 pt-6"
    >
        <JobCard v-for="job in jobInstances" :key="job.id" :job="job" />
    </div>
</template>
