<script setup>
import Button from '@/Components/UI/Button/Button.vue';
import { SparklesIcon } from '@heroicons/vue/24/outline';
import { ref } from 'vue';

const props = defineProps({
    class: {
        type: String,
        default: '',
    },
    toggleEdit: {
        type: Function,
        default: () => {},
    },
    openModal: {
        type: Function,
        default: () => {},
    },
});

const edit = ref(false);
</script>

<template>
    <div
        :class="class"
        class="group container relative flex w-full flex-col items-center justify-between overflow-hidden rounded bg-white p-6 shadow-md md:w-[15em]"
    >
        <h2 class="mb-2 text-center text-lg font-bold uppercase">
            Owner Panel
        </h2>
        <div class="mt-3 flex w-[10em] flex-col items-center gap-3 md:w-full">
            <Button
                class="w-full"
                :options="{
                    shape: 'pill',
                    color: 'gold',
                    leftIcon: SparklesIcon,
                }"
                >Feature</Button
            >
            <Button
                id="edit-company-button"
                @click="
                    () => {
                        edit = !edit;
                        toggleEdit(edit);
                    }
                "
                class="w-full"
                :options="{
                    shape: 'pill',
                }"
                >Edit Company</Button
            >
            <Button
                class="w-full"
                :options="{
                    shape: 'pill',
                    color: 'green',
                }"
                @click="openModal('add_job')"
                >Create job</Button
            >
        </div>
    </div>
</template>
