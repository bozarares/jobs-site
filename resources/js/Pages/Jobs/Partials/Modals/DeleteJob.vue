<script setup>
// TODO: Change form with axios
// TODO: Change controller so it will send a response

import axios from 'axios';
import { router, usePage } from '@inertiajs/vue3';

const props = defineProps({
    closeModal: { type: Function, default: () => {} },
});

const page = usePage();
const job = page.props.job;
const company = page.props.job.company;

const error = ref('');
const submit = () => {
    axios
        .delete(route('job.delete', { job: job.slug }))
        .then((response) => {
            if (response.status === 200) {
                router.visit(
                    route('companies.show', {
                        company: company.slug,
                    }),
                );
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
        class="container relative mx-auto flex max-h-[40em] max-w-2xl flex-col gap-8 overflow-auto rounded bg-white p-8 shadow dark:bg-zinc-800 dark:text-zinc-100"
    >
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-bold uppercase">
                {{ $t('actions.deleteJob') }}
            </h2>
            <div>
                <Heroicons:xMark
                    class="h-6 cursor-pointer"
                    @click="closeModal()"
                />
            </div>
        </div>

        <div class="flex flex-col gap-2 overflow-auto">
            <h2 class="text-center font-bold text-red-500">
                {{ $t('messages.deleteJobConfirmation') }}
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
                >{{ $t('common.delete') }}</Button
            >
            <Button
                @click="
                    () => {
                        closeModal();
                    }
                "
                class=""
                :options="{ color: 'gray', shape: 'pill' }"
                >{{ $t('common.cancel') }}</Button
            >
        </div>
    </div>
</template>
