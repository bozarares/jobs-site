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
});

const edit = ref(false);
</script>

<template>
    <div
        :class="class"
        class="group relative container bg-white w-full md:w-[15em] flex flex-col justify-between p-6 rounded shadow-md overflow-hidden items-center"
    >
        <h2 class="text-lg uppercase font-bold mb-2 text-center">
            Owner Panel
        </h2>
        <div class="flex flex-col items-center gap-3 mt-3 w-[10em] md:w-full">
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
                >Create job</Button
            >
        </div>
    </div>
</template>
