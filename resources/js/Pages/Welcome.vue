<script setup>
import { Button } from '@/Components/UI';
import Input from '@/Components/UI/Input/Input.vue';
import JobCard from '@/Components/JobCard.vue';
import {
    FunnelIcon,
    GlobeEuropeAfricaIcon,
    MagnifyingGlassIcon,
} from '@heroicons/vue/24/outline';
import { Head, Link } from '@inertiajs/vue3';
import Job from '@/Models/Job';
const props = defineProps({
    jobs: {
        type: Array,
        required: true,
    },
});

const jobInstances = computed(() => {
    return props.jobs.map((jobData) => new Job(jobData));
});
</script>

<template>
    <Head title="Home" />
    <div
        class="flex w-full justify-center gap-4 bg-white/75 py-1 text-sm font-bold text-zinc-800 dark:bg-zinc-800/95 dark:text-zinc-100"
    >
        <Link :href="route('profile.applications')">{{
            $t('sections.applications')
        }}</Link>
        <Link :href="route('profile.applications')">{{
            $t('sections.likes')
        }}</Link>
    </div>
    <div
        class="flex w-full flex-col items-center space-y-4 bg-zinc-50 p-6 dark:bg-zinc-800/90 dark:text-zinc-100"
    >
        <h1
            class="gradient max-w-screen-lg bg-gradient-to-r from-blue-300 to-blue-500 bg-clip-text pb-2 text-center text-4xl font-bold text-transparent"
        >
            {{ $t('messages.welcome') }}
        </h1>
        <div class="flex flex-col items-center gap-2 md:flex-row md:gap-4">
            <Input
                :label="$t('labels.searchForAJob')"
                :options="{ size: 'small', leftIcon: MagnifyingGlassIcon }"
            />
            <Input
                :label="$t('labels.location')"
                :options="{ size: 'small', leftIcon: GlobeEuropeAfricaIcon }"
            />
            <div class="flex w-full gap-2">
                <Button :options="{ color: 'blue' }">{{
                    $t('common.search')
                }}</Button>
                <Button :options="{ leftIcon: FunnelIcon }">{{
                    $t('labels.filter')
                }}</Button>
            </div>
        </div>
    </div>
    <div
        class="flex w-full max-w-screen-lg flex-wrap justify-center gap-2 pt-6"
    >
        <JobCard v-for="job in jobInstances" :key="job.slug" :job="job" />
    </div>
</template>
