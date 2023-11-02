<script setup>
// TODO: Change form with axios
// TODO: Change controller so it will send a response

import { Button, Timeline } from '@/Components/UI';

import {
    ArrowDownTrayIcon,
    ChevronLeftIcon,
    ChevronRightIcon,
    EyeIcon,
    XMarkIcon,
} from '@heroicons/vue/24/outline';
import { router, usePage } from '@inertiajs/vue3';
import { ref, watch, computed, reactive } from 'vue';

import PDFIcon from '@/Components/UI/Icons/PDFIcon.vue';

import UserApplicationCard from './Partials/UserApplicationCard.vue';
import EmployerPanelCard from './Partials/EmployerPanelCard.vue';

const props = defineProps({
    closeModal: { type: Function, default: () => {} },
    application: { type: Object, required: true },
    previous: { type: Number, default: null },
    next: { type: Number, default: null },
});

const page = usePage();
const job = page.props.job;
const application = reactive({
    current: props.application,
    previous: props.previous,
    next: props.next,
});

const user = computed(() => application.current.user || {});
const jobHistoryTimeline = ref([]);
const educationHistoryTimeline = ref([]);

watch(
    () => user.value.job_history,
    (newValue, oldValue) => {
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
    (newValue, oldValue) => {
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
</script>

<template>
    <div
        class="flex h-full w-full flex-col-reverse items-center justify-center overflow-auto overflow-x-hidden md:flex-row-reverse md:gap-4"
    >
        <EmployerPanelCard
            :application="application.current"
            :click-application="
                (id) => {
                    router.visit(
                        route('job.show.application', {
                            job: application.current.job.slug,
                            application: id,
                        }),
                    );
                }
            "
        />
        <div
            class="container relative z-50 flex max-h-[35em] max-w-[40em] flex-col overflow-auto rounded bg-white py-8 shadow"
        >
            <div class="flex items-center justify-between px-4 md:px-8">
                <h2 class="text-lg font-bold uppercase text-black/60">
                    Application
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
                                        router.visit(
                                            route('job.show.application', {
                                                job: application.current.job
                                                    .slug,
                                                application: application.next,
                                            }),
                                        );
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
                                        router.visit(
                                            route('job.show.application', {
                                                job: application.current.job
                                                    .slug,
                                                application: application.next,
                                            }),
                                        );
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
                <h2 class="text-lg font-bold uppercase text-black/60">Files</h2>
                <div
                    class="group/file relative flex gap-2 px-2 py-2"
                    v-for="file in application.current.files"
                    :key="file.id"
                >
                    <PDFIcon v-if="file.extension === 'pdf'" class="h-6" />{{
                        file.name
                    }}
                    <div
                        class="absolute inset-0 left-0 hidden h-full w-full items-center justify-center gap-4 opacity-0 backdrop-blur-[1px] transition-all duration-200 ease-in-out md:flex md:group-hover/file:opacity-100"
                    >
                        <Button
                            as="a"
                            is="a"
                            target="_blank"
                            :href="route('users.files.show', file.servername)"
                            :options="{
                                leftIcon: EyeIcon,
                                color: 'green',
                                shape: 'pill',
                            }"
                        />
                        <Button
                            as="a"
                            is="a"
                            target="_blank"
                            :href="
                                route('users.files.download', file.servername)
                            "
                            :options="{
                                leftIcon: ArrowDownTrayIcon,
                                shape: 'pill',
                            }"
                        />
                    </div>
                </div>
                <div class="flex w-full flex-col gap-4">
                    <div>
                        <h2 class="text-lg font-bold uppercase text-black/60">
                            Description
                        </h2>
                        <div
                            class="ql-editor prose"
                            v-html="user.description"
                        ></div>
                    </div>
                    <div class="pr-8">
                        <h2 class="text-lg font-bold uppercase text-black/60">
                            Job history
                        </h2>
                        <Timeline :items="jobHistoryTimeline" />
                    </div>
                    <div class="pr-8">
                        <h2 class="text-lg font-bold uppercase text-black/60">
                            Education history
                        </h2>
                        <Timeline :items="educationHistoryTimeline" />
                    </div>
                    <div>
                        <h2 class="text-lg font-bold uppercase text-black/60">
                            Skills
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
    </div>
</template>
