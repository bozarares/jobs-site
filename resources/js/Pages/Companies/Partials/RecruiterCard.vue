<script setup>
import Button from '@/Components/UI/Button/Button.vue';
import { SparklesIcon } from '@heroicons/vue/24/outline';

const props = defineProps({
    class: {
        type: String,
        default: '',
    },
});
</script>

<template>
    <div
        :class="class"
        class="group relative container bg-white w-full md:w-[15em] flex flex-col justify-between p-6 rounded shadow-md overflow-hidden items-center"
    >
        <h2 class="text-lg uppercase font-bold mb-2 text-center">
            Owner Panel
        </h2>
        <div class="flex flex-col items-center gap-3 mt-3 w-[10em] md:w-full">
            <Button
                class="w-full"
                :options="{
                    shape: 'pill',
                    color: 'gold',
                    leftIcon: SparklesIcon,
                }"
                >Feature</Button
            >
            <Button
                class="w-full"
                :options="{
                    shape: 'pill',
                    color: 'green',
                }"
                >Create job</Button
            >
            <Button
                class="w-full"
                :options="{
                    shape: 'pill',
                    color: 'green',
                }"
                >Edit company</Button
            >
        </div>
    </div>
</template>
