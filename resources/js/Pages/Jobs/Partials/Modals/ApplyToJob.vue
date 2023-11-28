<script setup>
// TODO: Change form with axios
// TODO: Change controller so it will send a response

import axios from 'axios';
import { router, usePage } from '@inertiajs/vue3';
import { useLocaleStore } from '@/Stores/localeStore';
import { useModalStore } from '@/Stores/modalStore';
import { useUserStore } from '@/Stores/userStore';

const props = defineProps({
    closeModal: { type: Function, default: () => {} },
});

const modalStore = useModalStore();
const localeStore = useLocaleStore();
const userStore = useUserStore();
const page = usePage();

const job = modalStore.args?.job ?? page.props.job;
const completion = ref(Math.round(userStore.currentUser.profileCompletion));

const files = ref([]);
const error = ref('');
const submit = () => {
    if (files.value.length === 0) {
        error.value = 'You must select at least one file';
        return;
    }
    axios
        .post(route('job.apply', { job: job.slug }), {
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

onMounted(async () => {
    const response = await axios.post(route('get.localized.data'), {
        locale: localeStore.locale,
    });
    userStore.currentUser.job_history = response.data.localizedData.jobHistory;
    userStore.currentUser.education_history =
        response.data.localizedData.educationHistory;
    userStore.currentUser.description = response.data.localizedData.description;
});
</script>

<template>
    <div
        class="container relative mx-auto flex max-h-[40em] max-w-2xl flex-col gap-8 overflow-auto rounded bg-white p-8 shadow dark:bg-zinc-800 dark:text-zinc-100"
    >
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-bold uppercase">
                {{ $t('common.apply.self') }}
            </h2>
            <div>
                <Heroicons:xMark
                    class="h-6 cursor-pointer"
                    @click="closeModal()"
                />
            </div>
        </div>

        <div class="flex flex-col gap-2 overflow-auto">
            <div class="flex flex-col">
                <h2 class="font-bold">
                    {{ $t('sections.profile') }}: {{ completion }}%
                </h2>
                <ul
                    class="list-inside list-disc text-sm text-red-500"
                    v-if="completion != 100"
                >
                    <li
                        v-if="
                            userStore.currentUser.description &&
                            userStore.currentUser.description === ''
                        "
                    >
                        {{ $t('messages.missingFields.description') }}
                    </li>
                    <li v-if="userStore.currentUser.avatar === null">
                        {{ $t('messages.missingFields.avatar') }}
                    </li>
                    <li
                        v-if="
                            userStore.currentUser.job_history &&
                            userStore.currentUser.job_history.length === 0
                        "
                    >
                        {{ $t('messages.missingFields.jobHistory') }}
                    </li>
                    <li
                        v-if="
                            userStore.currentUser.education_history &&
                            userStore.currentUser.education_history.length === 0
                        "
                    >
                        {{ $t('messages.missingFields.educationHistory') }}
                    </li>
                    <li v-if="userStore.currentUser.skills.length === 0">
                        {{ $t('messages.missingFields.skills') }}
                    </li>
                    <li v-if="userStore.currentUser.files.length === 0">
                        {{ $t('messages.missingFields.files') }}
                    </li>
                    <li
                        v-if="
                            !userStore.currentUser.social_github ||
                            !userStore.currentUser.social_linkedin ||
                            !userStore.currentUser.social_facebook
                        "
                    >
                        {{ $t('messages.missingFields.social') }}
                    </li>
                </ul>
                <div v-if="completion != 100">
                    <h2>
                        {{ $t('messages.incompleteProfile') }}
                    </h2>
                </div>
            </div>
            <div class="mt-8 flex flex-col">
                <h2 class="text-lg font-bold uppercase">
                    {{ $t('labels.files.self') }}
                </h2>
                <div
                    class="mt-2 flex w-full justify-between border-b border-black/10 pb-5 first:mt-4 last:border-b-0 last:pb-0"
                    v-for="file in userStore.currentUser.files"
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
                {{ $t('messages.applyNote') }}
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
            :options="{ color: 'blue', shape: 'pill' }"
            >{{ $t('common.apply.self') }}</Button
        >
    </div>
</template>
