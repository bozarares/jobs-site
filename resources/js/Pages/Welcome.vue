<script setup>
import JobCard from '@/Components/JobCard.vue';

import mdiMagnify from '~icons/mdi/magnify';
import mdiEarth from '~icons/mdi/earth';
import mdiFilter from '~icons/mdi/filter';

import { Head, Link, router } from '@inertiajs/vue3';
import Job from '@/Models/Job';
import axios from 'axios';
const props = defineProps({
    jobs: {
        type: Array,
        required: true,
    },
    lastPage: Number,
});

const jobs = ref(props.jobs);
const loading = ref(false);
const page = ref(1);

const handleScroll = () => {
    if (
        window.innerHeight + window.scrollY >=
            document.body.offsetHeight - 1000 &&
        !loading.value
    ) {
        if (page.value < props.lastPage) {
            page.value++;
            loadMore();
        }
    }
};

const loadMore = async () => {
    loading.value = true;
    const response = await axios.get(
        route('api.load.jobs', {
            page: page.value,
        }),
    );
    if (response.data.length > 0) {
        jobs.value = [...jobs.value, ...response.data];
    }
    loading.value = false;
};

onMounted(() => {
    window.addEventListener('scroll', handleScroll);
});

onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll);
});

const jobInstances = computed(() => {
    return jobs.value.map((jobData) => new Job(jobData));
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
        <Link :href="route('profile.likes')">{{ $t('sections.likes') }}</Link>
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
                :options="{
                    background: 'none',
                    size: 'small',
                    leftIcon: mdiMagnify,
                    borderStyle: 'border-bottom',
                }"
            />
            <Input
                :label="$t('labels.location')"
                :options="{
                    background: 'none',
                    size: 'small',
                    leftIcon: mdiEarth,
                    borderStyle: 'border-bottom',
                }"
            />
            <div class="flex w-full items-center justify-center gap-2">
                <Button :options="{ color: 'blue' }">{{
                    $t('common.search')
                }}</Button>
                <Button :options="{ leftIcon: mdiFilter }">{{
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
    <div
        class="mt-12 text-2xl font-bold text-zinc-800 dark:text-zinc-100"
        v-if="page === lastPage"
    >
        Thats all folks 😁
    </div>
</template>
