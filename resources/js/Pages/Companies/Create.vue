<script setup>
import { Button, Input } from '@/Components/UI';
import { QuillEditor } from '@vueup/vue-quill';
import '@vueup/vue-quill/dist/vue-quill.snow.css';
import { onBeforeUnmount, ref } from 'vue';
import { useForm, usePage } from '@inertiajs/vue3';
import vueFilePond from 'vue-filepond';
import { watch } from 'vue';

import 'filepond/dist/filepond.min.css';

import 'filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css';

import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type';
import FilePondPluginImagePreview from 'filepond-plugin-image-preview';

// Create component
const FilePond = vueFilePond(
    FilePondPluginFileValidateType,
    FilePondPluginImagePreview,
);

const page = usePage();
const csrfToken = page.props.csrf;

const filePondRef = ref(null);
const quillRef = ref(null);

const submited = ref(false);
const form = useForm({
    name: '',
    code: '',
    location: '',
    description: '',
    logo: null,
});

watch(filePondRef, (newValue, oldValue) => {
    const files = newValue.getFiles();
    if (files.length > 0) {
        form.logo = files[0].file;
        console.log(form.logo);
    }
});

const submit = () => {
    const htmlContent = quillRef.value.getHTML();
    form.description = htmlContent;
    form.post(route('companies.store'), {
        onFinish: () => {
            submited.value = true;
            form.reset('name', 'code', 'location', 'description');
        },
    });
};

onBeforeUnmount(() => {
    if (!submited.value) {
        if (filePondRef.value) {
            const files = filePondRef.value.getFiles();
            if (files.length > 0) {
                filePondRef.value.removeFiles(files[0]);
            }
        }
    }
});

const onProcessFile = (error, file) => {
    if (error) {
        console.log(error);
        return;
    } else {
        form.logo = file.serverId;
    }
};
</script>

<template>
    <div
        class="container max-w-screen-md mt-24 bg-white p-4 rounded-md shadow-md flex flex-col items-center"
    >
        <h2 class="text-2xl font-bold">Thank you for trusting us!</h2>
        <h3 class="text-lg font-semibold text-gray-700 mb-4">
            Before recruiting you need to add your company
        </h3>
        <div class="flex flex-col gap-4 items-center max-w-lg w-full">
            <Input v-model="form.name" label="Company name" />
            <Input v-model="form.code" label="Unique registration code" />
            <Input v-model="form.location" label="Location" />
            <FilePond
                @processfile="onProcessFile"
                :server="{
                    process: route('uploads.process'),
                    revert: {
                        url: route('uploads.destroy'),
                        method: 'DELETE',
                    },
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                    },
                }"
                ref="filePondRef"
                class="w-full"
                label-idle="Drop the company logo here..."
                accepted-file-types="image/jpeg, image/png"
            />
            <p class="text-center text-lg">Add a description to your company</p>
            <div>
                <QuillEditor ref="quillRef" toolbar="essential" theme="snow" />
            </div>
            <div class="flex w-full justify-center gap-4">
                <Button
                    @click="
                        () => {
                            submit();
                        }
                    "
                    class="w-24 mt-4"
                    :options="{ color: 'green' }"
                    >Create</Button
                >
            </div>
        </div>
    </div>
</template>
