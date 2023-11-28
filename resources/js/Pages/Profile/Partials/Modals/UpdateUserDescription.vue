<script setup>
import toolbarOptions from '@/quillToolBarConfig';
import { useProfileStore } from '@/Stores/profileStore';
import { useUpdateUserData } from '@/Composables/useUpdateUserData';
import { useUserStore } from '@/Stores/userStore';

const props = defineProps({
    closeModal: { type: Function, default: () => {} },
});
const quillRef = ref(null);

const profileStore = useProfileStore();
const userStore = useUserStore();
const { updateDescription } = useUpdateUserData(userStore.currentUser);

const submit = async () => {
    updateDescription(quillRef.value.getHTML());
    props.closeModal();
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
            quillRef.value.setHTML(profileStore.description);
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
        class="container relative mx-auto flex max-h-[35em] max-w-lg flex-col gap-8 overflow-auto rounded bg-white p-8 shadow dark:bg-zinc-800 dark:text-zinc-100"
    >
        <div class="flex items-center justify-between">
            <h2
                class="text-lg font-bold uppercase text-zinc-500 dark:text-zinc-300"
            >
                {{ $t('labels.description.edit') }}
            </h2>
            <Heroicons:xMark
                class="h-6 cursor-pointer text-zinc-500 dark:text-zinc-300"
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
            :options="{ color: 'blue', shape: 'pill' }"
            >{{ $t('common.save') }}</Button
        >
    </div>
</template>

<style>
.ql-toolbar .ql-stroke {
    @apply dark:fill-none dark:stroke-zinc-100;
}

.ql-picker-options {
    @apply dark:!bg-zinc-800 dark:text-zinc-100 dark:selection:bg-black dark:hover:bg-zinc-600;
}

.ql-picker-item .ql-selected {
    @apply dark:bg-zinc-600;
}
.ql-snow.ql-toolbar button.ql-active,
.ql-snow .ql-toolbar button.ql-active,
.ql-snow.ql-toolbar .ql-picker-label.ql-active,
.ql-snow .ql-toolbar .ql-picker-label.ql-active,
.ql-snow.ql-toolbar .ql-picker-item.ql-selected,
.ql-snow .ql-toolbar .ql-picker-item.ql-selected {
    @apply dark:bg-zinc-600;
}

.ql-snow.ql-toolbar button:hover,
.ql-snow .ql-toolbar button:hover,
.ql-snow.ql-toolbar button:focus,
.ql-snow .ql-toolbar button:focus,
.ql-snow.ql-toolbar .ql-picker-label:hover,
.ql-snow .ql-toolbar .ql-picker-label:hover,
.ql-snow.ql-toolbar .ql-picker-item:hover,
.ql-snow .ql-toolbar .ql-picker-item:hover {
    @apply dark:bg-zinc-600;
}

.ql-snow.ql-toolbar button.ql-active,
.ql-snow .ql-toolbar button.ql-active,
.ql-snow.ql-toolbar .ql-picker-label.ql-active,
.ql-snow .ql-toolbar .ql-picker-label.ql-active,
.ql-snow.ql-toolbar .ql-picker-item.ql-selected,
.ql-snow .ql-toolbar .ql-picker-item.ql-selected {
    @apply dark:text-zinc-100;
}
</style>
