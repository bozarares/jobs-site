<script setup>
import { useForm, usePage } from '@inertiajs/vue3';
import VueFilePond from 'vue-filepond';
import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type';
import { ref } from 'vue';
import 'filepond/dist/filepond.min.css';
import filePondServer from '@/filePondConfig';
import { Button } from '@/Components/UI';
import Input from '@/Components/UI/Input/Input.vue';

import { onMounted } from 'vue';
import { onBeforeUnmount } from 'vue';
import axios from 'axios';
import { XMarkIcon } from '@heroicons/vue/24/outline';

const props = defineProps({
    closeModal: { type: Function, default: () => {} },
});

const page = usePage();
const csrfToken = page.props.csrf;
const files = ref(page.props.user.files);

const FilePond = VueFilePond(FilePondPluginFileValidateType);
const filePondRef = ref(null);

const form = useForm({
    id: null,
    name: '',
    extension: '',
    servername: '',
});

const submited = ref(false);
const uploaded = ref(false);
const error = ref(null);

function sanitizeFilename(filename) {
    return filename.replace(/[^\w\s]/g, '').replace(/\s+/g, '_');
}

const addFile = () => {
    submited.value = true;
    form.name = sanitizeFilename(form.name);
    if (uploaded.value === false) {
        return;
    }
    form.post(route('profile.update.files'), {
        onError: () => {
            submited.value = false;
        },
        onFinish: () => {},
        onSuccess: (response) => {
            uploaded.value = false;
            filePondRef.value.removeFiles();
            form.reset();
            files.value = response.props.user.files;
        },
    });
};

const removeFile = (fileId) => {
    form.id = fileId;
    form.delete(route('profile.update.files'), {
        onSuccess: (response) => {
            files.value = response.props.user.files;
            form.reset('id');
        },
    });
};

const onProcessFile = (error, file) => {
    if (error) {
        console.log(error);
        return;
    }
    console.log(file);
    form.name = sanitizeFilename(file.filenameWithoutExtension);
    form.extension = file.fileExtension;
    form.servername = file.serverId;
};

const onProcessFileProgress = (file, progress) => {
    if (progress === 1) {
        uploaded.value = true;
        console.log('onProcessFileProgress uploaded', uploaded.value);
    }
};

const revertFiles = () => {
    if (uploaded.value === true && filePondRef.value) {
        const filepondFile = filePondRef.value.getFile();
        if (filepondFile) {
            axios.delete(route('uploads.destroy'), {
                data: filepondFile.serverId,
            });
            filePondRef.value.removeFile(filepondFile);
        }
    }
};

const beforeUnload = () => {
    revertFiles();
};

onMounted(() => {
    window.addEventListener('beforeunload', beforeUnload);
});

onBeforeUnmount(() => {
    revertFiles();
    window.removeEventListener('beforeunload', beforeUnload);
});
</script>

<template>
    <Teleport to="body">
        <div class="fixed inset-0 z-50 flex items-center justify-center gap-4">
            <!-- Backdrop -->
            <div
                @click="closeModal()"
                class="absolute inset-0 bg-black opacity-50"
            ></div>
            <div
                class="container relative ml-auto mr-auto flex max-h-[25em] max-w-lg flex-col overflow-auto rounded bg-white p-8 shadow md:mr-0"
            >
                <div
                    v-if="files.length !== 0"
                    v-for="file in files"
                    :key="file.id"
                    class="flex items-center justify-between border-b-2 pb-4 pt-4 first:pt-0 last:border-b-0 last:pb-0"
                >
                    <div>{{ file.name }}</div>
                    <XMarkIcon
                        class="w-5 cursor-pointer"
                        @click="removeFile(file.id)"
                    />
                </div>
                <div v-else class="font-bold text-gray-600">
                    You don't have any files
                </div>
            </div>
            <div
                class="container relative ml-auto mr-auto flex max-h-[35em] max-w-lg flex-col gap-8 overflow-auto rounded bg-white p-8 shadow md:ml-0"
            >
                <h2 class="text-lg font-bold uppercase text-black/60">
                    Edit Files
                </h2>

                <div class="flex flex-col gap-4 overflow-auto">
                    <Input v-model="form.name" label="File Name" />
                    <FilePond
                        id="avatar-upload"
                        @processfile="onProcessFile"
                        @processfileprogress="onProcessFileProgress"
                        :server="
                            filePondServer(
                                csrfToken,
                                form.servername,
                                form.extension,
                            )
                        "
                        ref="filePondRef"
                        class="w-full"
                        label-idle="Drop the file here..."
                        accepted-file-types="application/pdf"
                    />
                </div>
                <p
                    v-if="form.errors.extension"
                    class="text-sm font-bold text-red-600"
                >
                    {{ form.errors.extension }}
                </p>
                <div class="flex flex-col gap-2">
                    <Button
                        @click="addFile"
                        class=""
                        :options="{ color: 'green', shape: 'pill' }"
                        >Add</Button
                    >
                </div>
            </div>
        </div>
    </Teleport>
</template>
