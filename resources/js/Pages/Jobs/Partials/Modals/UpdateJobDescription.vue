<script setup>
// TODO: Change form with axios
// TODO: Change controller so it will send a response

import { onMounted, ref, watch, onBeforeMount } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import { Button } from '@/Components/UI';
import axios from 'axios';
import { XMarkIcon } from '@heroicons/vue/24/outline';
import toolbarOptions from '@/quillToolBarConfig';
import { markRaw } from 'vue';

const props = defineProps({
    closeModal: { type: Function, default: () => {} },
});
const quillRef = ref(null);

const page = usePage();
const job = page.props.job;

const description = ref(job.description);

const submit = () => {
    const htmlContent = quillRef.value.getHTML();
    description.value = htmlContent;
    axios
        .patch(route('job.update.description', { job: job.slug }), {
            description: description.value,
        })
        .then((response) => {
            router.reload({ preserveState: true, only: ['job'] });
            props.closeModal();
        });
};

const isClient = ref(false);

onBeforeMount(() => {
    isClient.value = typeof window !== 'undefined';
});

let QuillEditor = ref(null);
watch(
    () => quillRef.value,
    () => {
        if (quillRef.value) {
            quillRef.value.setHTML(description.value);
        }
    },
);

onMounted(async () => {
    if (isClient.value) {
        const { QuillEditor: QuillImport } = await import('@vueup/vue-quill');
        QuillEditor.value = markRaw(QuillImport);
    }
});
</script>

<template>
    <div
        class="container relative mx-auto flex max-h-[35em] max-w-2xl flex-col gap-8 rounded bg-white p-8 shadow"
    >
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-bold uppercase text-black/60">Edit User</h2>
            <XMarkIcon
                class="h-6 cursor-pointer text-black/60"
                @click="closeModal()"
            />
        </div>

        <div class="flex h-auto max-h-[30em] flex-col overflow-hidden pb-20">
            <QuillEditor
                v-if="isClient && QuillEditor"
                name="Company description"
                ref="quillRef"
                :toolbar="toolbarOptions"
                theme="snow"
            />
        </div>
        <Button
            @click="submit"
            class=""
            :options="{ color: 'green', shape: 'pill' }"
            >Save</Button
        >
    </div>
</template>
