<script setup>
// TODO: Change form with axios
// TODO: Change controller so it will send a response

import { Button, Input, Switch } from '@/Components/UI';
import pkg from 'lodash';
const { debounce } = pkg;
import axios from 'axios';
import { XMarkIcon } from '@heroicons/vue/24/outline';
import { router, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    closeModal: { type: Function, default: () => {} },
});

const page = usePage();
const user = page.props.auth.user;

const error = ref('');
const submit = () => {
    axios
        .delete(route('job.delete', { job: page.props.job.slug }))
        .then((response) => {
            if (response.status === 200) {
                router.reload({ preserveState: true });
                props.closeModal();
            }
        })
        .catch((err) => {
            error.value = err.response
                ? err.response.data.message
                : 'An error occurred';
        });
};
</script>

<template>
    <div
        class="container relative mx-auto flex max-h-[40em] max-w-2xl flex-col gap-8 overflow-auto rounded bg-white p-8 shadow"
    >
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-bold uppercase text-black/60">Delete</h2>
            <div>
                <XMarkIcon
                    class="h-6 cursor-pointer text-black/60"
                    @click="closeModal()"
                />
            </div>
        </div>

        <div class="flex flex-col gap-2 overflow-auto">
            <h2 class="text-center font-bold text-red-500">
                Deleting will close all applications and remove access to the
                files
            </h2>
        </div>
        <div class="flex w-full items-center justify-center gap-4">
            <Button
                @click="
                    () => {
                        submit();
                    }
                "
                class=""
                :options="{ color: 'red', shape: 'pill' }"
                >Delete</Button
            >
            <Button
                @click="
                    () => {
                        closeModal();
                    }
                "
                class=""
                :options="{ color: 'gray', shape: 'pill' }"
                >Cancel</Button
            >
        </div>
    </div>
</template>
