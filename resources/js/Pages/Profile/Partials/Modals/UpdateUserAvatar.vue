<script setup>
// TODO: Change form with axios
// TODO: Change controller so it will send a response

import { useForm, usePage } from '@inertiajs/vue3';
import 'filepond/dist/filepond.min.css';
import filePondServer from '@/filePondConfig';

const isClient = ref(false);

onBeforeMount(() => {
    isClient.value = typeof window !== 'undefined';
});

let FilePond = ref(null);

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

const props = defineProps({
    closeModal: { type: Function, default: () => {} },
});

const page = usePage();
const csrfToken = page.props.csrf;

const filePondRef = ref(null);

const form = useForm({
    avatar: null,
    avatar_extension: null,
});

const submited = ref(false);
const uploaded = ref(false);

const submit = () => {
    if (uploaded.value === false) {
        return;
    }
    submited.value = true;
    form.post(route('profile.update.avatar'), {
        onError: () => {
            submited.value = false;
        },
        onFinish: () => {
            uploaded.value = false;
            props.closeModal();
        },
    });
};

const onProcessFile = (error, file) => {
    if (error) {
        return;
    }
    uploaded.value = true;
    form.avatar = file.serverId;
    form.avatar_extension = file.fileExtension;
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
        class="container relative mx-auto flex max-h-[35em] max-w-lg flex-col gap-8 overflow-auto rounded bg-white p-8 shadow dark:bg-zinc-800"
    >
        <div class="flex items-center justify-between">
            <h2
                class="text-lg font-bold uppercase text-zinc-500 dark:text-zinc-300"
            >
                {{ $t('labels.avatar.edit') }}
            </h2>
            <Heroicons:xMark
                class="h-6 cursor-pointer text-zinc-500 dark:text-zinc-300"
                @click="closeModal()"
            />
        </div>

        <div class="overflow-auto">
            <FilePond
                v-if="isClient && FilePond"
                id="avatar-upload"
                @processfile="onProcessFile"
                :server="
                    filePondServer(
                        csrfToken,
                        form.avatar,
                        form.avatar_extension,
                    )
                "
                ref="filePondRef"
                class="w-full bg-black"
                :label-idle="$t('labels.avatar.drop')"
                accepted-file-types="image/jpeg, image/png"
            />
        </div>
        <Button
            @click="submit"
            class=""
            :options="{ color: 'blue', shape: 'pill' }"
            >{{ $t('common.save') }}</Button
        >
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
