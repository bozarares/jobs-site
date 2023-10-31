<script setup>
import { Button } from '@/Components/UI';
import Input from '@/Components/UI/Input/Input.vue';
import JobCard from '@/Components/JobCard.vue';
import {
    FunnelIcon,
    GlobeEuropeAfricaIcon,
    MagnifyingGlassIcon,
} from '@heroicons/vue/24/outline';
import { Link } from '@inertiajs/vue3';
const props = defineProps({
    jobs: {
        type: Array,
        required: true,
    },
});
</script>

<template>
    <div
        class="flex w-full justify-center gap-4 bg-white/75 py-1 text-sm font-bold text-gray-800"
    >
        <Link :href="route('profile.applications')">My applications</Link>
        <Link>Likes</Link>
    </div>
    <div class="flex w-full flex-col items-center space-y-4 bg-gray-50 p-6">
        <h1 class="text-center text-2xl font-semibold text-gray-800">
            Welcome to Joben, the ultimate job finding solution!
        </h1>
        <div class="flex flex-col items-center gap-2 md:flex-row md:gap-4">
            <Input
                label="Search for jobs"
                :options="{ size: 'small', leftIcon: MagnifyingGlassIcon }"
            />
            <Input
                label="Location"
                :options="{ size: 'small', leftIcon: GlobeEuropeAfricaIcon }"
            />
            <div class="flex gap-2">
                <Button>Search</Button>
                <Button :options="{ leftIcon: FunnelIcon }">Filter</Button>
            </div>
        </div>
    </div>
    <div
        class="flex w-full max-w-screen-lg flex-wrap justify-center gap-2 bg-gray-100 pt-6"
    >
        <JobCard v-for="job in jobs" :key="job.slug" :job="job" />
    </div>
</template>
