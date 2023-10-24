<script setup>
import { StarIcon } from '@heroicons/vue/24/outline';

const props = defineProps({ rating: Number });
</script>

<template>
    <div class="flex items-center gap-0.5">
        <div v-for="n in 5">
            <StarIcon
                :key="'full-' + n"
                v-if="rating >= n"
                class="h-4 w-4 fill-yellow-500 text-yellow-500"
            />
            <StarIcon
                :key="'half-' + n"
                v-else-if="rating >= n - 0.5"
                class="h-4 w-4 fill-yellow-500 text-yellow-500"
            />
            <StarIcon
                :key="'empty-' + n"
                v-else
                class="h-4 w-4 text-gray-300"
            />
        </div>
        <p class="ml-1 text-sm font-bold text-gray-500">{{ props.rating }}/5</p>
    </div>
</template>

<style scoped></style>
