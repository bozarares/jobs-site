<script setup>
import { useForm, usePage } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';
import { Button } from '@/Components/UI';
import { QuillEditor } from '@vueup/vue-quill';
import '@vueup/vue-quill/dist/vue-quill.snow.css';
import toolbarOptions from '@/quillToolBarConfig';

const props = defineProps({
    closeModal: { type: Function, default: () => {} },
});
const quillRef = ref(null);

const page = usePage();
const user = page.props.auth.user;

const form = useForm({
    description: user.description,
});

const submit = () => {
    const htmlContent = quillRef.value.getHTML();
    form.description = htmlContent;
    form.post(route('profile.update.description'), {
        onFinish: () => {
            props.closeModal();
        },
    });
};
onMounted(() => {
    quillRef.value.setHTML(user.description);
});
</script>

<template>
    <div
        class="container relative mx-auto flex max-h-[35em] max-w-2xl flex-col gap-8 rounded bg-white p-8 shadow"
    >
        <h2 class="text-lg font-bold uppercase text-black/60">Edit User</h2>

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

<style>
/* .ql-container {
    margin-bottom: 20px !important;
} */
.ql-editor {
    margin-bottom: 20px !important;
    overflow: auto !important;
    min-height: 20em !important;
}
</style>
