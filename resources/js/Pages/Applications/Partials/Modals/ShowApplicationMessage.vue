<script setup>
import { usePage } from '@inertiajs/vue3';

import 'filepond/dist/filepond.min.css';

const props = defineProps({
    args: {
        type: Object,
        required: true,
    },
    closeModal: { type: Function, default: () => {} },
});

const page = usePage();
</script>

<template>
    <div
        class="container relative mx-auto flex max-h-[35em] max-w-lg flex-col gap-8 overflow-auto rounded bg-white p-8 shadow dark:bg-zinc-800 dark:text-zinc-100"
    >
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-bold uppercase">
                {{ $t('common.message') }}
            </h2>
            <Heroicons:xMark class="h-6 cursor-pointer" @click="closeModal()" />
        </div>

        <div class="overflow-auto">
            <div class="prose dark:text-zinc-100">{{ args.message }}</div>
        </div>
    </div>
</template>
