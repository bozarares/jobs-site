<script setup>
import { useModalStore } from '@/Stores/modalStore';

const modalStore = useModalStore();
const props = defineProps({
    edit: {
        type: Boolean,
        default: false,
    },
    title: {
        type: String,
        default: '',
    },
    modal: {
        type: String,
        default: null,
    },
    id: {
        type: String,
        default: null,
    },
    idEdit: {
        type: String,
        default: null,
    },
    class: {
        type: String,
        default: '',
    },
});
</script>

<template>
    <div
        :id="props.edit ? props.idEdit : props.id"
        @click="
            () => {
                if (props.edit && props.modal) {
                    modalStore.openModal(props.modal);
                }
            }
        "
        class="group container relative flex w-full flex-col gap-4 rounded bg-white p-6 text-zinc-700 shadow-md dark:bg-zinc-800 dark:text-zinc-100"
        :class="{
            'cursor-pointer': props.edit,
            [props.class]: props.class,
        }"
    >
        <h2
            v-if="edit"
            class="absolute right-0 top-0 pr-2 font-extrabold text-zinc-500 transition-all duration-150 ease-in-out group-hover:text-zinc-600"
        >
            {{ $t('labels.clickToEdit.field') }}
        </h2>

        <h2
            class="text-lg font-bold uppercase text-zinc-500 dark:text-zinc-300"
        >
            {{ props.title }}
        </h2>
        <slot />
    </div>
</template>
