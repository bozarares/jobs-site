<script setup>
import UserCard from './Partials/UserCard.vue';
import SocialsCard from './Partials/SocialsCard.vue';
import { Button, Timeline } from '@/Components/UI';
import { ref } from 'vue';
import { PencilSquareIcon } from '@heroicons/vue/24/outline';
import UpdateAvatar from './Partials/Modals/UpdateAvatar.vue';
import ModalWrapper from './Partials/ModalWrapper.vue';
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { watch } from 'vue';
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

const edit = ref(false);
</script>

<template>
    <div class="flex w-full flex-col items-center space-y-4 bg-gray-50 p-6">
        <h1 class="text-center text-2xl font-semibold text-gray-800">
            Profile
        </h1>
        <div class="flex flex-col items-center gap-2 md:flex-row md:gap-4">
            <div class="flex gap-2">
                <Button @click="edit = !edit" :options="{ shape: 'pill' }"
                    >{{ edit ? 'Stop edit' : 'Edit' }} Profile</Button
                >
            </div>
        </div>
    </div>
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
        </div>

        <!-- Right side -->
        <div
            class="flex w-full flex-grow flex-col justify-start gap-4 md:w-3/4"
        >
            <div
                class="group container relative flex w-full flex-col gap-4 rounded bg-white p-6 shadow-md"
            >
                <Button
                    @click="openModal('description')"
                    v-if="edit"
                    class="absolute right-0 top-0 m-4"
                    :options="{ leftIcon: PencilSquareIcon }"
                ></Button>
                <h2 class="text-lg font-bold uppercase text-black/60">
                    Description
                </h2>
                <div v-html="user.description" />
            </div>
            <div
                class="container relative flex w-full flex-col gap-4 rounded bg-white p-6 shadow-md"
            >
                <Button
                    @click="openModal('jobs')"
                    v-if="edit"
                    class="absolute right-0 top-0 m-4"
                    :options="{ leftIcon: PencilSquareIcon }"
                ></Button>
                <h2 class="text-lg font-bold uppercase text-black/60">
                    Job history
                </h2>
                <Timeline :items="jobHistoryTimeline" />
            </div>
            <div
                class="container relative flex w-full flex-col gap-4 rounded bg-white p-6 shadow-md"
            >
                <Button
                    v-if="edit"
                    class="absolute right-0 top-0 m-4"
                    :options="{ leftIcon: PencilSquareIcon }"
                ></Button>
                <h2 class="text-lg font-bold uppercase text-black/60">
                    Education
                </h2>
                <div>University 1</div>
                <div>University 2</div>
            </div>
            <div
                class="container relative flex w-full flex-col gap-4 rounded bg-white p-6 shadow-md"
            >
                <Button
                    v-if="edit"
                    class="absolute right-0 top-0 m-4"
                    :options="{ leftIcon: PencilSquareIcon }"
                ></Button>
                <h2 class="text-lg font-bold uppercase text-black/60">
                    Skills
                </h2>
                <div>Laravel</div>
                <div>Vue.js</div>
            </div>
            <div
                class="container relative flex w-full flex-col gap-4 rounded bg-white p-6 shadow-md"
            >
                <Button
                    v-if="edit"
                    class="absolute right-0 top-0 m-4"
                    :options="{ leftIcon: PencilSquareIcon }"
                ></Button>
                <h2 class="text-lg font-bold uppercase text-black/60">Files</h2>
                <div>CV_FirstName_LastName</div>
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
