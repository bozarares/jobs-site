<script setup>
import { Button } from '@/Components/UI';
import Input from '@/Components/UI/Input/Input.vue';
import JobCard from '@/Components/JobCard.vue';
import {
    FunnelIcon,
    GlobeEuropeAfricaIcon,
    MagnifyingGlassIcon,
} from '@heroicons/vue/24/outline';
</script>

<template>
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
        class="grid w-full max-w-screen-lg grid-cols-1 items-center justify-items-center gap-2 bg-gray-100 p-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4"
    >
        <JobCard />
        <JobCard />
        <JobCard />
        <JobCard />
    </div>
</template>
