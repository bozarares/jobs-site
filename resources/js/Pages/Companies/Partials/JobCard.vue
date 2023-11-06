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
    <div
        @touchstart="handleTouchStart"
        @touchend="handleTouchEnd"
        class="group/jobs container prose relative flex flex-col items-center justify-between rounded-sm px-4 outline outline-gray-200 md:flex-row"
    >
        <div class="flex flex-col md:items-start">
            <div class="text-center text-xl font-bold md:text-start">
                {{ job.title }}
            </div>
            <div class="text-center text-xs font-bold md:text-start">
                {{ experiences }}
            </div>
        </div>

        <div
            class="flex flex-row flex-wrap items-center justify-center gap-2 py-2 md:flex-col md:items-start md:justify-between"
        >
            <div class="flex items-center gap-2 text-sm">
                <span class="font-bold uppercase"
                    >{{ $t('labels.location') }}
                </span>
                {{ job.location }} ({{ job.type }})
            </div>
            <div class="flex items-center gap-2 text-sm">
                <span class="font-bold uppercase">{{
                    $t('labels.salary')
                }}</span>
                {{ salary }}
            </div>
        </div>
        <div
            class="absolute left-0 hidden h-full w-full items-center justify-center gap-4 opacity-0 backdrop-blur-[2.5px] transition-all duration-200 ease-in-out md:flex md:group-hover/jobs:opacity-100"
        >
            <div class="flex gap-1">
                <Button
                    :options="{ leftIcon: HandThumbDownIcon, shape: 'pill' }"
                />
                <Button
                    :options="{ leftIcon: HandThumbUpIcon, shape: 'pill' }"
                />
            </div>
            <Button
                :is="Link"
                as="button"
                :href="route('jobs.show', { job: job.slug })"
                :options="{ color: 'green', shape: 'pill' }"
                >Go to job</Button
            >
        </div>

        <Transition
            enter-from-class="scale-90 opacity-0 translate-x-8"
            enter-active-class="transition-all duration-200"
            enter-to-class="scale-100 opacity-100"
            leave-from-class="scale-100 opacity-100"
            leave-active-class="transition-all duration-200"
            leave-to-class="scale-90 opacity-0 translate-x-8"
        >
            <div
                v-if="revealButtons"
                class="absolute right-0 mr-8 flex h-full items-center justify-center gap-4 md:hidden"
            >
                <div class="flex gap-1">
                    <Button
                        :options="{
                            leftIcon: HandThumbDownIcon,
                            shape: 'pill',
                        }"
                    />
                    <Button
                        :options="{ leftIcon: HandThumbUpIcon, shape: 'pill' }"
                    />
                </div>
            </div>
        </Transition>
    </div>
</template>
