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
import { watch } from 'vue';

const props = defineProps({
    closeModal: { type: Function, default: () => {} },
});

const page = usePage();
const user = page.props.auth.user;
const completion = Math.round(user.profileCompletion);
const files = ref([]);
const error = ref('');
const submit = () => {
    if (files.value.length === 0) {
        error.value = 'You must select at least one file';
        return;
    }
    axios
        .post(route('job.apply', { job: page.props.job.slug }), {
            files: files.value,
        })
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
            <h2 class="text-lg font-bold uppercase text-black/60">Apply</h2>
            <div>
                <XMarkIcon
                    class="h-6 cursor-pointer text-black/60"
                    @click="closeModal()"
                />
            </div>
        </div>

        <div class="flex flex-col gap-2 overflow-auto">
            <div class="flex flex-col">
                <h2 class="font-bold">
                    Profile completion:
                    {{ completion }}%
                </h2>
                <ul
                    class="list-inside list-disc text-sm text-red-500"
                    v-if="completion != 100"
                >
                    <li v-if="user.description === ''">No description</li>
                    <li v-if="user.avatar === null">No Avatar</li>
                    <li v-if="user.job_history.length === 0">
                        No Job History added
                    </li>
                    <li v-if="user.education_history.length === 0">
                        No Edication History added
                    </li>
                    <li v-if="user.skills.length === 0">No Skills added</li>
                    <li v-if="user.files.length === 0">No Files added</li>
                    <li
                        v-if="
                            !user.social_github ||
                            !user.social_linkedin ||
                            !user.social_facebook
                        "
                    >
                        No Socials added
                    </li>
                </ul>
                <div v-if="completion != 100">
                    <h2>
                        Note: Completing your profile will increase your chances
                        of getting hired
                    </h2>
                </div>
            </div>
            <div class="mt-8 flex flex-col">
                <h2 class="text-lg font-bold uppercase text-black/60">Files</h2>
                <div
                    class="mt-2 flex w-full justify-between border-b border-black/10 pb-5 first:mt-4 last:border-b-0 last:pb-0"
                    v-for="file in user.files"
                    :key="file.servername"
                >
                    <div>{{ file.name }}</div>
                    <Switch
                        :on-change="
                            (value) => {
                                if (value) files.push(file.id);
                                else files = files.filter((f) => f != file.id);
                            }
                        "
                    />
                </div>
            </div>
            <h2 class="mt-4 text-justify font-bold">
                Note: Files will be available only for the company you are
                applying to, for the duration of the application process
            </h2>
        </div>
        <h2 v-if="error" class="text-center text-sm font-bold text-red-500">
            {{ error }}
        </h2>
        <Button
            @click="
                () => {
                    submit();
                }
            "
            class=""
            :options="{ color: 'green', shape: 'pill' }"
            >Apply</Button
        >
    </div>
</template>
