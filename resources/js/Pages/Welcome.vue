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
    <div class="flex flex-col items-center w-full space-y-4 bg-gray-50 p-6">
        <h1 class="text-2xl font-semibold text-gray-800 text-center">
            Welcome to Joben, the ultimate job finding solution!
        </h1>
        <div class="flex md:flex-row flex-col items-center gap-2 md:gap-4">
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
        class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 items-center w-full p-6 bg-gray-100 gap-2 max-w-screen-lg justify-items-center"
    >
        <JobCard />
        <JobCard />
        <JobCard />
        <JobCard />
    </div>
</template>
