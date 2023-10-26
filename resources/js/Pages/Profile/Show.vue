<script setup>
import UserCard from './Partials/UserCard.vue';
import SocialsCard from './Partials/SocialsCard.vue';
import { Button, Timeline } from '@/Components/UI';
import { ref } from 'vue';
import { ArrowDownTrayIcon, EyeIcon } from '@heroicons/vue/24/outline';
import ModalWrapper from './Partials/ModalWrapper.vue';

import { watch } from 'vue';
import SettingsCard from './Partials/SettingsCard.vue';
const props = defineProps({
    user: {
        type: Object,
        required: true,
    },
});

const modal = ref(null);

const openModal = (modalName) => {
    if (modal.value === null) modal.value = modalName;
};
const jobHistoryTimeline = ref([]); // initial este un array gol
const educationHistoryTimeline = ref([]);

watch(
    () => props.user.job_history,
    (newValue, oldValue) => {
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
    () => props.user.education_history,
    (newValue, oldValue) => {
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

const edit = ref(false);
</script>

<template>
    <div
        class="mt-12 flex w-full max-w-screen-lg flex-wrap justify-center gap-8 p-6 md:flex-nowrap"
    >
        <!-- Left side -->
        <div class="flex w-full flex-col gap-4 md:w-auto">
            <UserCard
                :edit="edit"
                :user="user"
                :open-modal="
                    (type) => {
                        openModal(type);
                    }
                "
            />
            <SocialsCard
                :edit="edit"
                :user="user"
                :open-modal="
                    (type) => {
                        openModal(type);
                    }
                "
            />
            <SettingsCard
                :toggle-edit="
                    (value) => {
                        edit = value;
                    }
                "
            />
        </div>

        <!-- Right side -->
        <div
            class="flex w-full flex-grow flex-col justify-start gap-4 md:w-3/4"
        >
            <div
                @click="
                    () => {
                        if (edit) {
                            openModal('description');
                        }
                    }
                "
                class="group container relative flex w-full flex-col gap-4 rounded bg-white p-6 shadow-md"
                :class="{
                    'cursor-pointer': edit,
                }"
            >
                <h2
                    v-if="edit"
                    class="absolute bottom-0 right-0 pr-2 font-extrabold text-gray-500 transition-all duration-150 ease-in-out group-hover:text-black"
                >
                    Click field to edit
                </h2>

                <h2 class="text-lg font-bold uppercase text-black/60">
                    Description
                </h2>
                <div class="ql-editor prose" v-html="user.description" />
            </div>
            <div
                @click="
                    () => {
                        if (edit) {
                            openModal('jobs');
                        }
                    }
                "
                :class="{
                    'cursor-pointer': edit,
                }"
                class="group container relative flex w-full flex-col gap-4 rounded bg-white p-6 shadow-md"
            >
                <h2
                    v-if="edit"
                    class="absolute bottom-0 right-0 pr-2 font-extrabold text-gray-500 transition-all duration-150 ease-in-out group-hover:text-black"
                >
                    Click field to edit
                </h2>
                <h2 class="text-lg font-bold uppercase text-black/60">
                    Job history
                </h2>
                <Timeline :items="jobHistoryTimeline" />
            </div>
            <div
                @click="
                    () => {
                        if (edit) {
                            openModal('education');
                        }
                    }
                "
                :class="{
                    'cursor-pointer': edit,
                }"
                class="group container relative flex w-full flex-col gap-4 rounded bg-white p-6 shadow-md"
            >
                <h2
                    v-if="edit"
                    class="absolute bottom-0 right-0 pr-2 font-extrabold text-gray-500 transition-all duration-150 ease-in-out group-hover:text-black"
                >
                    Click field to edit
                </h2>

                <h2 class="text-lg font-bold uppercase text-black/60">
                    Education
                </h2>
                <Timeline :items="educationHistoryTimeline" />
            </div>
            <div
                @click="
                    () => {
                        if (edit) {
                            openModal('skills');
                        }
                    }
                "
                :class="{
                    'cursor-pointer': edit,
                }"
                class="group container relative flex w-full flex-col gap-4 rounded bg-white p-6 shadow-md"
            >
                <h2
                    v-if="edit"
                    class="absolute bottom-0 right-0 pr-2 font-extrabold text-gray-500 transition-all duration-150 ease-in-out group-hover:text-black"
                >
                    Click field to edit
                </h2>
                <h2 class="text-lg font-bold uppercase text-black/60">
                    Skills
                </h2>
                <div class="flex flex-wrap gap-4">
                    <div
                        v-for="skill in user.skills"
                        :key="skill.id"
                        class="rounded bg-gray-800 px-2 py-1 text-white"
                    >
                        {{ skill.name }}
                    </div>
                </div>
            </div>
            <div
                @click="
                    () => {
                        if (edit) {
                            openModal('files');
                        }
                    }
                "
                :class="{
                    'cursor-pointer': edit,
                }"
                class="group container relative flex w-full flex-col gap-4 rounded bg-white p-6 shadow-md"
            >
                <h2
                    v-if="edit"
                    class="absolute bottom-0 right-0 pr-2 font-extrabold text-gray-500 transition-all duration-150 ease-in-out group-hover:text-black"
                >
                    Click field to edit
                </h2>

                <h2 class="text-lg font-bold uppercase text-black/60">Files</h2>
                <div class="flex flex-wrap items-center gap-4">
                    <div
                        v-for="file in user.files"
                        :key="file.id"
                        class="flex items-center gap-4 rounded border px-4 py-2 text-black"
                    >
                        {{ file.name }}
                        <div class="flex items-center gap-1">
                            <Button
                                as="a"
                                is="a"
                                target="_blank"
                                :href="
                                    route('users.files.show', file.servername)
                                "
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
                                    route(
                                        'users.files.download',
                                        file.servername,
                                    )
                                "
                                :options="{
                                    leftIcon: ArrowDownTrayIcon,

                                    shape: 'pill',
                                }"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <ModalWrapper
        :modal="modal"
        :close-modal="
            () => {
                modal = null;
            }
        "
    />
</template>
