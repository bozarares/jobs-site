<script setup>
import { useForm, usePage } from '@inertiajs/vue3';
import VueFilePond from 'vue-filepond';
import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type';
import { onBeforeUnmount, onMounted, ref } from 'vue';
import 'filepond/dist/filepond.min.css';
import { Button } from '@/Components/UI';
import filePondServer from '@/filePondConfig';
import axios from 'axios';

const props = defineProps({
    closeModal: { type: Function, default: () => {} },
});

const page = usePage();
const csrfToken = page.props.csrf;
const company = page.props.company;

const FilePond = VueFilePond(FilePondPluginFileValidateType);
const filePondRef = ref(null);

const form = useForm({
    logo: null,
    extension: null,
});

const submited = ref(false);
const uploaded = ref(false);

const submit = () => {
    if (uploaded.value === false) {
        return;
    }
    submited.value = true;
    form.patch(route('companies.update.logo', { company: company.slug }), {
        onError: () => {
            submited.value = false;
        },
        onFinish: () => {
            props.closeModal();
        },
    });
};

const onProcessFile = (error, file) => {
    if (error) {
        return;
    }
    uploaded.value = true;

    form.logo = file.serverId;
    form.extension = file.fileExtension;
};

const revertFiles = () => {
    if (uploaded.value === true && filePondRef.value) {
        const filepondFile = filePondRef.value.getFile();
        console.log(filepondFile.serverId, filepondFile.fileExtension);
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
        class="container relative mx-auto flex max-h-[35em] max-w-lg flex-col gap-8 overflow-auto rounded bg-white p-8 shadow"
    >
        <h2 class="text-lg font-bold uppercase text-black/60">Edit Logo</h2>

        <div class="overflow-auto">
            <FilePond
                id="avatar-upload"
                @processfile="onProcessFile"
                :server="filePondServer(csrfToken, form.logo, form.extension)"
                ref="filePondRef"
                class="w-full"
                label-idle="Drop the logo here..."
                accepted-file-types="image/jpeg, image/png"
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
