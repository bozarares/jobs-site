<script setup>
import UserCard from './Partials/UserCard.vue';
import SocialsCard from './Partials/SocialsCard.vue';
import SettingsCard from './Partials/SettingsCard.vue';
import { Timeline } from '@/Components/UI';
import ContentCard from '@/Components/ContentCard.vue';
import FileAction from '@/Components/FileAction.vue';
import { useLocaleStore } from '@/Stores/localeStore';
import { useProfileStore } from '@/Stores/profileStore';
import { Head } from '@inertiajs/vue3';
import { useCurrentUser } from '@/Composables/useCurrentUser';

const currentUser = useCurrentUser();
const props = defineProps({
    localizedData: {
        type: Object,
        required: true,
    },
});

const localeStore = useLocaleStore();
const profileStore = useProfileStore();

const jobHistory = ref(props.localizedData.jobHistory);
const educationHistory = ref(props.localizedData.educationHistory);
const description = ref(props.localizedData.description);
const language = ref(props.localizedData.language);

const jobHistoryTimeline = ref([]);
const educationHistoryTimeline = ref([]);

watch(
    () => profileStore.profileWatcherLocale,
    async (newValue) => {
        if (newValue) {
            profileStore.resetProfileWatcher();
            const response = await axios.post(route('get.localized.data'), {
                locale: localeStore.profileLocale,
            });
            jobHistory.value = response.data.localizedData.jobHistory;
            educationHistory.value =
                response.data.localizedData.educationHistory;
            description.value = response.data.localizedData.description;
            profileStore.setData(response.data.localizedData);
        }
    },
);

const getLocalizedData = async (newLangauage) => {
    const response = await axios.post(route('get.localized.data'), {
        locale: newLangauage,
    });
    jobHistory.value = response.data.localizedData.jobHistory;
    educationHistory.value = response.data.localizedData.educationHistory;
    description.value = response.data.localizedData.description;
    language.value = response.data.localizedData.language;
    localeStore.setProfileLocale(newLangauage);
    profileStore.setData(response.data.localizedData);
};

watch(
    () => jobHistory.value,
    (newValue) => {
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
    () => educationHistory.value,
    (newValue) => {
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

onMounted(() => {
    profileStore.setData(props.localizedData);
});

const edit = ref(false);
</script>

<template>
    <Head title="Profile" />

    <div
        class="mt-12 flex w-full max-w-screen-lg flex-wrap justify-center gap-8 p-6 md:flex-nowrap"
    >
        <!-- Left side -->
        <div class="flex w-full flex-col items-center gap-4 md:w-auto">
            <UserCard
                :edit="edit"
                :user="currentUser"
                :on-language-change="
                    (newLangauage) => {
                        getLocalizedData(newLangauage);
                    }
                "
                :language="language"
            />
            <SocialsCard :edit="edit" :user="currentUser" />
            <SettingsCard
                :user="currentUser"
                :toggle-edit="
                    (value) => {
                        edit = value;
                    }
                "
            />
        </div>

        <!-- Right side -->
        <div
            class="flex w-full flex-grow flex-col items-center justify-start gap-4 md:w-3/4"
        >
            <!-- Description -->
            <ContentCard
                :title="$t('labels.description.self')"
                :edit="edit"
                id-edit="profile-description-edit"
                modal="userDescription"
            >
                <div
                    class="ql-editor prose dark:!text-zinc-100"
                    v-html="description"
                />
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
                        v-for="skill in currentUser.skills"
                        :key="skill.id"
                        class="select-none rounded-full px-3 py-1 outline outline-zinc-400 transition-all duration-150 ease-in-out hover:scale-105"
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
                    <div v-for="file in currentUser.files" :key="file.id">
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
