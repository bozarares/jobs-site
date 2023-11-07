<script setup>
import { Button, Switch, Timeline } from '@/Components/UI';
import { Link } from '@inertiajs/vue3';
import JobApplicationCard from '@/Components/JobApplicationCard.vue';
import { ref } from 'vue';
import { computed } from 'vue';
const props = defineProps({
    applications: {
        type: Array,
    },
});

const closedJobs = ref(false);
const filteredApplication = computed(() => {
    return props.applications.filter((application) => {
        return closedJobs.value ? true : application.status !== 'closed';
    });
});
</script>

<template>
    <div
        class="flex w-full justify-center gap-4 bg-white/75 py-1 text-sm font-bold text-gray-800"
    >
        <Link :href="route('profile.applications')">{{
            $t('sections.applications')
        }}</Link>
        <Link>{{ $t('sections.likes') }}</Link>
    </div>
    <div class="flex w-full items-center justify-center gap-2 bg-gray-50 p-6">
        <h1 class="text-lg font-bold text-black/70">
            {{ $t('actions.showClosedJobs') }}
        </h1>
        <Switch
            :on-change="
                (value) => {
                    closedJobs = value;
                }
            "
        />
    </div>
    <div
        class="flex w-full max-w-screen-lg flex-wrap justify-center gap-2 bg-gray-100 pt-6"
    >
        <JobApplicationCard
            v-for="application in filteredApplication"
            :key="application.id"
            :application="application"
        />
    </div>
</template>
