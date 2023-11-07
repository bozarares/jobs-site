<script setup>
import UserCard from './Partials/UserCard.vue';
import SocialsCard from './Partials/SocialsCard.vue';
import SettingsCard from './Partials/SettingsCard.vue';
import { Button, Timeline } from '@/Components/UI';
import { ref, watch } from 'vue';
import { ArrowDownTrayIcon, EyeIcon } from '@heroicons/vue/24/outline';
import { useModalStore } from '@/Stores/modalStore';
import ContentCard from '@/Components/ContentCard.vue';
import FileAction from '@/Components/FileAction.vue';

const modalStore = useModalStore();
const props = defineProps({
    user: {
        type: Object,
        required: true,
    },
});

const jobHistoryTimeline = ref([]);
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
            <UserCard :edit="edit" :user="user" />
            <SocialsCard :edit="edit" :user="user" />
            <SettingsCard
                :user="props.user"
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
            <!-- Description -->
            <ContentCard
                :title="$t('labels.description.self')"
                :edit="edit"
                id-edit="profile-description-edit"
                modal="userDescription"
            >
                <div class="ql-editor prose" v-html="user.description" />
            </ContentCard>

            <!-- Job History -->
            <ContentCard
                :title="$t('labels.jobHistory.self')"
                :edit="edit"
                id-edit="profile-jobs-edit"
                modal="userJobs"
            >
                <Timeline :items="jobHistoryTimeline" />
            </ContentCard>

            <!-- Education History -->
            <ContentCard
                :title="$t('labels.educationHistory.self')"
                :edit="edit"
                id-edit="profile-education-edit"
                modal="userEducation"
            >
                <Timeline :items="educationHistoryTimeline" />
            </ContentCard>

            <!-- Skills -->
            <ContentCard
                :title="$tc('labels.skills.self', 2)"
                :edit="edit"
                id-edit="profile-skills-edit"
                modal="userSkills"
            >
                <div class="flex flex-wrap gap-2">
                    <div
                        v-for="skill in user.skills"
                        :key="skill.id"
                        class="select-none rounded-full px-3 py-1 outline outline-gray-400 transition-all duration-150 ease-in-out hover:scale-105"
                    >
                        {{ skill.name }}
                    </div>
                </div>
            </ContentCard>

            <!-- Files -->
            <ContentCard
                :title="$t('labels.files.self')"
                :edit="edit"
                id-edit="profile-files-edit"
                modal="userFiles"
            >
                <div class="flex flex-col gap-4">
                    <div v-for="file in user.files" :key="file.id">
                        <FileAction
                            :file="file"
                            show-route="users.files.show"
                            download-route="users.files.download"
                        />
                    </div>
                </div>
            </ContentCard>
        </div>
    </div>
</template>
