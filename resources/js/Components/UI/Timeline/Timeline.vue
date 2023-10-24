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
            class="flex gap-6 group"
        >
            <div
                class="flex-shrink-0 text-gray-500 text-sm min-w-[10em] font-bold hidden md:block"
            >
                {{ formatDate(item.start, item.end) }}
            </div>
            <!-- Middle -->
            <div class="relative flex items-center flex-col w-5">
                <div class="absolute w-0.5 h-full bg-gray-500"></div>
                <div
                    class="absolute w-6 h-6 bg-blue-500 rounded-full border-4 border-white"
                ></div>
            </div>
            <div class="relative mb-10 group-last:mb-0 items-center w-full">
                <div class="rounded-lg bg-white">
                    <div
                        class="text-lg font-semibold text-gray-700 flex items-center gap-2"
                    >
                        {{ item.title }}
                        <div class="text-sm text-gray-500 md:hidden">
                            {{ formatDate(item.start, item.end) }}
                        </div>
                    </div>
                    <p class="text-gray-500 font-bold text-justify">
                        {{ item.subtitle }}
                    </p>
                    <p class="mt-2 text-gray-600 text-justify">
                        {{ item.description }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>
