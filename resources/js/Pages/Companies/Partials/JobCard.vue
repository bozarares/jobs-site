<script setup>
import { ref } from 'vue';
import { HandThumbDownIcon, HandThumbUpIcon } from '@heroicons/vue/24/outline';
import { Button } from '@/Components/UI';
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    job: { type: Object, default: () => {} },
});

const experiences = computed(() => {
    return props.job.levels.join(', ');
});

const salary = computed(() => {
    if (props.job.salary === null) return 'Confidential';
    return `$${props.job.salary}`;
});

let touchStartX = 0;
let touchEndX = 0;

const revealButtons = ref(false);

function handleTouchStart(event) {
    touchStartX = event.touches[0].clientX;
}

function handleTouchEnd(event) {
    touchEndX = event.changedTouches[0].clientX;
    if (touchStartX > touchEndX + 100) {
        revealButtons.value = true;
    }
    if (touchStartX < touchEndX + 100) {
        revealButtons.value = false;
    }
}
</script>

<template>
    <Link
        :href="route('jobs.show', { job: job.slug })"
        class="container prose relative flex min-h-[5em] w-full flex-col items-center justify-between rounded-sm px-4 py-2 outline outline-gray-200 transition-all duration-150 ease-in-out hover:scale-[101%] lg:min-h-[15em] lg:max-w-[13em]"
    >
        <div class="flex flex-col lg:items-start">
            <div class="text-center text-xl font-bold">
                {{ job.title }}
            </div>
            <div class="text-center text-xs font-bold lg:text-start">
                {{ experiences }}
            </div>
        </div>

        <div
            class="flex w-full items-center justify-between gap-2 py-2 lg:flex-col lg:items-start lg:justify-between"
        >
            <div
                class="flex items-center justify-between gap-2 text-sm lg:w-full"
            >
                <span class="text-left font-bold uppercase"
                    >{{ $t('labels.location') }}
                </span>
                <span class="text-right text-xs"
                    >{{ job.location }} ({{ job.type }})</span
                >
            </div>
            <div
                class="flex items-center justify-between gap-2 text-sm lg:w-full"
            >
                <span class="font-bold uppercase">{{
                    $t('labels.salary')
                }}</span>
                <span class="text-right text-xs">{{ salary }}</span>
            </div>
        </div>
    </Link>
</template>
