<script setup>
import { ArrowDownTrayIcon, EyeIcon } from '@heroicons/vue/24/solid';
import { Button } from './UI';
import PDFIcon from './UI/Icons/PDFIcon.vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    file: {
        type: Object,
        required: true,
    },
    showRoute: { type: String, required: true },
    downloadRoute: { type: String, required: true },
});
</script>

<template>
    <div class="group/file relative flex items-center gap-2 px-2 py-2">
        <PDFIcon v-if="file.extension === 'pdf'" class="h-6" />{{ file.name }}
        <div class="flex items-center justify-center gap-2">
            <a
                class="transition-all duration-150 ease-in-out hover:scale-125"
                target="_blank"
                :href="route(props.showRoute, file.servername)"
            >
                <EyeIcon class="w-5"
            /></a>
            <a
                class="transition-all duration-150 ease-in-out hover:scale-125"
                target="_blank"
                :href="route(props.downloadRoute, file.servername)"
            >
                <ArrowDownTrayIcon class="w-5"
            /></a>
        </div>
    </div>
</template>
