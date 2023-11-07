<script setup>
import { Button, Timeline } from '@/Components/UI';

import axios from 'axios';
import {
    ChevronLeftIcon,
    ChevronRightIcon,
    XMarkIcon,
} from '@heroicons/vue/24/outline';

import UserApplicationCard from '../UserApplicationCard.vue';
import EmployerPanelCard from '../EmployerPanelCard.vue';

import { usePage } from '@inertiajs/vue3';

import {
    ref,
    onBeforeUnmount,
    reactive,
    computed,
    watch,
    onBeforeMount,
} from 'vue';
import FileAction from '@/Components/FileAction.vue';

const props = defineProps({
    closeModal: { type: Function, default: () => {} },
});

const page = usePage();
const job = page.props.job;
const applicationCache = ref(new Map());
const application = reactive({
    current: {},
    next: null,
    previous: null,
});
const user = computed(() => application.current.user || {});
const jobHistoryTimeline = ref([]);
const educationHistoryTimeline = ref([]);

watch(
    () => user.value.job_history,
    (newValue) => {
        if (!newValue) {
            return;
        }
        jobHistoryTimeline.value = newValue.map((item) => {
            return {
                start: new Date(item.start_date),
                end: item.end_date ? new Date(item.end_date) : undefined,
                title: item.company,
                subtitle: item.title,
                description: item.description,
            };
        });
    },
    { immediate: true },
);
watch(
    () => user.value.education_history,
    (newValue) => {
        if (!newValue) {
            return;
        }
        educationHistoryTimeline.value = newValue.map((item) => {
            return {
                start: new Date(item.start_date),
                end: item.end_date ? new Date(item.end_date) : undefined,
                title: item.institution,
                subtitle: item.degree,
                description: item.field_of_study,
            };
        });
    },
    { immediate: true },
);

const updateUrl = (applicationId) => {
    if (!applicationId) {
        window.history.pushState({}, '', `/jobs/${job.slug}`);
        return;
    }
    const newUrl = `/jobs/${job.slug}/application/${applicationId}`;
    window.history.pushState({ applicationId }, '', newUrl);
};

const fetchApplication = async (applicationId = null) => {
    if (applicationCache.value.has(applicationId)) {
        const cachedApplication = applicationCache.value.get(applicationId);
        application.current = cachedApplication.current;
        application.next = cachedApplication.next;
        application.previous = cachedApplication.previous;
        return;
    }
    try {
        const response = await axios.post(
            route('job.get.application', { job: job.slug }),
            { application_id: applicationId },
        );
        application.current = response.data.application;
        application.next = response.data.next;
        application.previous = response.data.previous;

        applicationCache.value.set(application.current.id, {
            current: application.current,
            next: application.next,
            previous: application.previous,
        });

        if (application.current.id) {
            updateUrl(application.current.id);
        }
    } catch (error) {
        console.error('There was an error fetching the application:', error);
    }
};

onBeforeMount(() => {
    fetchApplication();
});

onBeforeUnmount(() => {
    updateUrl(null);
});
</script>

<template>
    <div
        class="flex h-full w-full flex-col-reverse items-center justify-center overflow-auto md:flex-row-reverse md:gap-4"
    >
        <EmployerPanelCard
            :application="application.current"
            :click-application="
                (id) => {
                    fetchApplication(id);
                }
            "
        />
        <div
            class="container relative z-50 flex max-h-[35em] max-w-[40em] flex-col overflow-auto rounded bg-white py-8 shadow"
        >
            <div class="flex items-center justify-between px-4 md:px-8">
                <h2 class="text-lg font-bold uppercase text-black/60">
                    {{ $t('labels.application') }}
                </h2>
                <div class="flex gap-2">
                    <div class="flex gap-4">
                        <ChevronLeftIcon
                            class="h-6"
                            :class="{
                                'cursor-not-allowed text-black/20':
                                    !application.previous,
                                'cursor-pointer text-black/60':
                                    application.previous,
                            }"
                            @click="
                                () => {
                                    if (application.previous)
                                        fetchApplication(application.previous);
                                }
                            "
                        />
                        <ChevronRightIcon
                            class="h-6"
                            :class="{
                                'cursor-not-allowed text-black/20':
                                    !application.next,
                                'cursor-pointer text-black/60':
                                    application.next,
                            }"
                            @click="
                                () => {
                                    if (application.next)
                                        fetchApplication(application.next);
                                }
                            "
                        />
                    </div>
                    <XMarkIcon
                        class="h-6 cursor-pointer text-black/60"
                        @click="closeModal()"
                    />
                </div>
            </div>
            <div
                class="flex w-full flex-col gap-4 overflow-auto p-4 py-4 md:p-8"
            >
                <UserApplicationCard :user="user" />
                <h2 class="text-lg font-bold uppercase text-black/60">
                    {{ $t('labels.files.self') }}
                </h2>
                <div
                    class="group/file relative flex justify-center gap-2 px-2 py-2"
                    v-for="file in application.current.files"
                    :key="file.id"
                >
                    <FileAction
                        :file="file"
                        show-route="users.files.show"
                        download-route="users.files.download"
                    />
                </div>
                <div>
                    <h2 class="text-lg font-bold uppercase text-black/60">
                        {{ $t('labels.description.self') }}
                    </h2>
                    <div
                        class="ql-editor prose"
                        v-html="user.description"
                    ></div>
                </div>
                <div class="pr-8">
                    <h2 class="text-lg font-bold uppercase text-black/60">
                        {{ $t('labels.jobHistory.self') }}
                    </h2>
                    <Timeline :items="jobHistoryTimeline" />
                </div>
                <div class="pr-8">
                    <h2 class="text-lg font-bold uppercase text-black/60">
                        {{ $t('labels.educationHistory.self') }}
                    </h2>
                    <Timeline :items="educationHistoryTimeline" />
                </div>
                <div>
                    <h2 class="text-lg font-bold uppercase text-black/60">
                        {{ $t('labels.skills.self') }}
                    </h2>
                    <div class="flex flex-wrap gap-2">
                        <div
                            v-for="skill in user.skills"
                            :key="skill.id"
                            class="select-none rounded-full px-3 py-1 outline outline-gray-400 transition-all duration-150 ease-in-out hover:scale-105"
                        >
                            {{ skill.name }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
