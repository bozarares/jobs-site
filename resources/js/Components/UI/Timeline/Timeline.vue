<script setup>
import { ref, computed } from 'vue';
import dayjs from 'dayjs';
import relativeTime from 'dayjs/plugin/relativeTime';

dayjs.extend(relativeTime);

const props = defineProps({
    items: {
        type: Array,
        required: true,
    },
});

const itemsSorted = computed(() => {
    return [...props.items].sort((a, b) =>
        dayjs(a.start).isAfter(dayjs(b.start)) ? -1 : 1,
    );
});

const formatDate = (start, end) => {
    const startDate = dayjs(start).format('MMM YYYY');
    const endDate = end ? dayjs(end).format('MMM YYYY') : 'To Date';
    return `${startDate} - ${endDate}`;
};
</script>

<template>
    <div class="">
        <div
            v-for="item in itemsSorted"
            :key="item.date"
            class="group flex gap-6"
        >
            <div
                class="hidden min-w-[10em] flex-shrink-0 text-sm font-bold text-zinc-500 md:block"
            >
                {{ formatDate(item.start, item.end) }}
            </div>
            <!-- Middle -->
            <div class="relative flex w-5 flex-col items-center">
                <div class="absolute h-full w-0.5 bg-zinc-500"></div>
                <div
                    class="absolute h-6 w-6 rounded-full border-4 border-white bg-blue-500 dark:border-zinc-800 dark:bg-blue-400"
                ></div>
            </div>
            <div class="relative mb-10 w-full items-center group-last:mb-0">
                <div class="rounded-lg">
                    <div
                        class="flex items-center gap-2 text-lg font-semibold text-zinc-700 dark:text-zinc-50"
                    >
                        {{ item.title }}
                        <div
                            class="text-sm text-zinc-500 dark:text-zinc-100 md:hidden"
                        >
                            {{ formatDate(item.start, item.end) }}
                        </div>
                    </div>
                    <p
                        class="text-justify font-bold text-zinc-500 dark:text-zinc-100"
                    >
                        {{ item.subtitle }}
                    </p>
                    <p
                        class="mt-2 text-justify text-zinc-600 dark:text-zinc-100"
                    >
                        {{ item.description }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>
