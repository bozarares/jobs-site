<script setup>
import { useForm, usePage } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';
import { Button } from '@/Components/UI';
import { QuillEditor } from '@vueup/vue-quill';
import '@vueup/vue-quill/dist/vue-quill.snow.css';
import toolbarOptions from '@/quillToolBarConfig';
import { XMarkIcon } from '@heroicons/vue/24/outline';

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
onMounted(() => {
    quillRef.value.setHTML(company.description);
});
</script>

<template>
    <div
        id="company-description-edit-modal"
        class="container relative mx-auto flex max-h-[35em] max-w-2xl flex-col gap-8 rounded bg-white p-8 shadow"
    >
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-bold uppercase text-black/60">
                Edit Description
            </h2>
            <XMarkIcon
                class="h-6 cursor-pointer text-black/60"
                @click="closeModal()"
            />
        </div>
        <div class="flex h-auto max-h-[30em] flex-col overflow-hidden pb-20">
            <QuillEditor
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

<style></style>
