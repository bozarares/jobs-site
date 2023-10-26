<script setup>
import { useForm, usePage } from '@inertiajs/vue3';
import VueFilePond from 'vue-filepond';
import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type';
import { ref } from 'vue';
import 'filepond/dist/filepond.min.css';
import { Button } from '@/Components/UI';
import filePondServer from '@/filePondConfig';

const props = defineProps({
    closeModal: { type: Function, default: () => {} },
});

const page = usePage();
const csrfToken = page.props.csrf;

const FilePond = VueFilePond(FilePondPluginFileValidateType);
const filePondRef = ref(null);

const form = useForm({
    avatar: null,
    extension: null,
});

const submited = ref(false);

const submit = () => {
    submited.value = true;
    form.post(route('profile.update.avatar'), {
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
        console.log(error);
        return;
    }
    console.log(file);
    form.avatar = file.serverId;
    form.extension = file.fileExtension;
};
</script>

<template>
    <div
        class="container relative mx-auto flex max-h-[35em] max-w-lg flex-col gap-8 overflow-auto rounded bg-white p-8 shadow"
    >
        <h2 class="text-lg font-bold uppercase text-black/60">Edit Avatar</h2>

        <div class="overflow-auto">
            <FilePond
                id="avatar-upload"
                @processfile="onProcessFile"
                :server="filePondServer(csrfToken, form.avatar, form.extension)"
                ref="filePondRef"
                class="w-full"
                label-idle="Drop the avatar here..."
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
