<script setup>
import { useForm, usePage } from '@inertiajs/vue3';
import { markRaw, onMounted, ref } from 'vue';
import { Button } from '@/Components/UI';
import toolbarOptions from '@/quillToolBarConfig';
import { XMarkIcon } from '@heroicons/vue/24/outline';
import { onBeforeMount } from 'vue';
import { watch } from 'vue';

const props = defineProps({
    closeModal: { type: Function, default: () => {} },
});
const quillRef = ref(null);

const page = usePage();
const company = page.props.company;

const form = useForm({
    description: company.description,
});

const submit = () => {
    const htmlContent = quillRef.value.getHTML();
    form.description = htmlContent;
    form.patch(
        route('companies.update.description', { company: company.slug }),
        {
            onFinish: () => {
                props.closeModal();
            },
        },
    );
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
            quillRef.value.setHTML(company.description);
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
        id="company-description-edit-modal"
        class="container relative mx-auto flex max-h-[35em] max-w-2xl flex-col gap-8 rounded bg-white p-8 shadow"
    >
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-bold uppercase text-black/60">
                {{ $t('labels.description.edit') }}
            </h2>
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
            >{{ $t('common.save') }}</Button
        >
    </div>
</template>
