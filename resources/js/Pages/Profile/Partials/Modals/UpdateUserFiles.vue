<script setup>
import { usePage } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3';

import 'filepond/dist/filepond.min.css';
import filePondServer from '@/filePondConfig';

import axios from 'axios';
import PDFIcon from '@/Components/UI/Icons/PDFIcon.vue';

const props = defineProps({
    closeModal: { type: Function, default: () => {} },
});

const isClient = ref(false);
const FilePond = ref(null);

onBeforeMount(() => {
    isClient.value = typeof window !== 'undefined';
});

onMounted(async () => {
    if (isClient.value) {
        const { default: VueFilePond } = await import('vue-filepond');
        const FilePondPluginFileValidateType = await import(
            'filepond-plugin-file-validate-type'
        );

        FilePond.value = markRaw(
            VueFilePond(FilePondPluginFileValidateType.default),
        );
    }
});

const page = usePage();
const csrfToken = page.props.csrf;
const files = ref(page.props.user.files);

const filePondRef = ref(null);

const fileName = ref('');
const fileExtension = ref('');
const fileServername = ref('');

const submited = ref(false);
const uploaded = ref(false);

function sanitizeFilename(filename) {
    return filename.replace(/[^\w\s]/g, '').replace(/\s+/g, '_');
}

const addFile = () => {
    submited.value = true;
    fileName.value = sanitizeFilename(fileName.value);
    if (uploaded.value === false) {
        return;
    }
    axios
        .post(route('profile.update.files.add'), {
            name: fileName.value,
            extension: fileExtension.value,
            servername: fileServername.value,
        })
        .then((response) => {
            if (response.data.success === true) {
                router.reload({
                    preserveState: true,
                });
                fileName.value = '';
                fileExtension.value = '';
                fileServername.value = '';
                filePondRef.value.removeFiles();
                files.value.push(response.data.file);
            }
        });
};

const removeFile = (fileId) => {
    axios
        .delete(route('profile.update.files.delete'), {
            data: {
                id: fileId,
            },
        })
        .then((response) => {
            if (response.data.success === true) {
                files.value = files.value.filter((file) => {
                    return file.id !== fileId;
                });
                router.reload({
                    preserveState: true,
                });
            }
        })
        .catch((error) => {
            console.log(error);
        });
};

const onProcessFile = (error, file) => {
    if (error) {
        console.log(error);
        return;
    }
    fileName.value = sanitizeFilename(file.filenameWithoutExtension);
    fileExtension.value = file.fileExtension;
    fileServername.value = file.serverId;
};

const onProcessFileProgress = (file, progress) => {
    if (progress === 1) {
        uploaded.value = true;
    }
};

const revertFiles = () => {
    if (uploaded.value === true && filePondRef.value) {
        const filepondFile = filePondRef.value.getFile();
        if (filepondFile) {
            axios.delete(route('uploads.destroy'), {
                data: {
                    filename: filepondFile.serverId,
                    extension: filepondFile.fileExtension,
                },
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
    <div
        id="files-modal"
        class="container relative flex max-h-[25em] max-w-lg flex-col overflow-y-auto rounded bg-white p-8 text-zinc-500 shadow dark:bg-zinc-800 dark:text-zinc-300"
    >
        <div
            v-if="files.length !== 0"
            v-for="file in files"
            :key="file.id"
            class="flex items-center justify-between border-b-2 pb-4 pt-4 first:pt-0 last:border-b-0 last:pb-0"
        >
            <div>{{ file.name }}</div>
            <div
                class="cursor-pointer"
                aria-label="Delete button"
                @click="removeFile(file.id)"
            >
                <Heroicons:xMark class="h-5" />
            </div>
        </div>
        <div v-else class="font-bold text-zinc-800 dark:text-zinc-200">
            {{ $t('fallbacks.files') }}
        </div>
    </div>
    <div
        class="container relative mx-auto flex max-h-[35em] max-w-lg flex-col gap-8 overflow-auto rounded bg-white p-8 text-zinc-500 shadow dark:bg-zinc-800 dark:text-zinc-300"
    >
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-bold uppercase">
                {{ $t('labels.files.edit') }}
            </h2>
            <Heroicons:xMark lass="h-6 cursor-pointer" @click="closeModal()" />
        </div>

        <div class="flex flex-col gap-4 overflow-auto">
            <Input
                v-model="fileName"
                :label="$t('labels.files.name')"
                name="file_name"
                :options="{
                    size: 'small',
                    borderStyle: 'border-bottom',
                    leftIcon: PDFIcon,
                }"
            />
            <FilePond
                v-if="isClient && FilePond"
                id="avatar-upload"
                @processfile="onProcessFile"
                @processfileprogress="onProcessFileProgress"
                :server="
                    filePondServer(csrfToken, fileServername, fileExtension)
                "
                ref="filePondRef"
                class="w-full"
                :label-idle="$t('labels.files.drop')"
                accepted-file-types="application/pdf"
            />
        </div>
        <!-- <p v-if="form.errors.extension" class="text-sm font-bold text-red-600">
            {{ form.errors.extension }}
        </p> -->
        <div class="flex flex-col gap-2">
            <Button
                @click="addFile"
                class=""
                :options="{ color: 'blue', shape: 'pill' }"
                >{{ $t('common.add') }}</Button
            >
        </div>
    </div>
</template>

<style>
.filepond--credits {
    @apply dark:text-zinc-100;
}
.filepond--drop-label {
    @apply dark:text-zinc-300;
}
.filepond--panel,
.filepond--panel-root {
    @apply dark:bg-zinc-700;
}
.filepond--wrapper {
    @apply bg-zinc-700/0;
}
</style>
