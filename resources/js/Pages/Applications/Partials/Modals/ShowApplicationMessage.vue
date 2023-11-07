<script setup>
import { usePage } from '@inertiajs/vue3';

import 'filepond/dist/filepond.min.css';

import { XMarkIcon } from '@heroicons/vue/24/outline';

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
        class="container relative mx-auto flex max-h-[35em] max-w-lg flex-col gap-8 overflow-auto rounded bg-white p-8 shadow"
    >
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-bold uppercase text-black/60">
                {{ $t('common.message') }}
            </h2>
            <XMarkIcon
                class="h-6 cursor-pointer text-black/60"
                @click="closeModal()"
            />
        </div>

        <div class="overflow-auto">
            <div class="prose">{{ args.message }}</div>
        </div>
    </div>
</template>
