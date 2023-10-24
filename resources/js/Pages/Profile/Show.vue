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
    <div class="flex flex-col items-center w-full space-y-4 bg-gray-50 p-6">
        <h1 class="text-2xl font-semibold text-gray-800 text-center">
            Profile
        </h1>
        <div class="flex md:flex-row flex-col items-center gap-2 md:gap-4">
            <div class="flex gap-2">
                <Button @click="edit = !edit" :options="{ shape: 'pill' }"
                    >{{ edit ? 'Stop edit' : 'Edit' }} Profile</Button
                >
            </div>
        </div>
    </div>
    <div
        class="flex flex-wrap md:flex-nowrap justify-center gap-8 w-full mt-12 p-6 max-w-screen-lg"
    >
        <!-- Left side -->
        <div class="flex flex-col gap-4 w-full md:w-auto">
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
            class="flex-grow w-full md:w-3/4 flex flex-col justify-start gap-4"
        >
            <div
                class="relative group container p-6 bg-white rounded shadow-md flex flex-col gap-4 w-full"
            >
                <Button
                    @click="openModal('description')"
                    v-if="edit"
                    class="absolute top-0 right-0 m-4"
                    :options="{ leftIcon: PencilSquareIcon }"
                ></Button>
                <h2 class="text-lg font-bold uppercase text-black/60">
                    Description
                </h2>
                <div v-html="user.description" />
            </div>
            <div
                class="relative container p-6 bg-white rounded shadow-md flex flex-col gap-4 w-full"
            >
                <Button
                    @click="openModal('jobs')"
                    v-if="edit"
                    class="absolute top-0 right-0 m-4"
                    :options="{ leftIcon: PencilSquareIcon }"
                ></Button>
                <h2 class="text-lg font-bold uppercase text-black/60">
                    Job history
                </h2>
                <Timeline :items="jobHistoryTimeline" />
            </div>
            <div
                class="relative container p-6 bg-white rounded shadow-md flex flex-col gap-4 w-full"
            >
                <Button
                    v-if="edit"
                    class="absolute top-0 right-0 m-4"
                    :options="{ leftIcon: PencilSquareIcon }"
                ></Button>
                <h2 class="text-lg font-bold uppercase text-black/60">
                    Education
                </h2>
                <div>University 1</div>
                <div>University 2</div>
            </div>
            <div
                class="relative container p-6 bg-white rounded shadow-md flex flex-col gap-4 w-full"
            >
                <Button
                    v-if="edit"
                    class="absolute top-0 right-0 m-4"
                    :options="{ leftIcon: PencilSquareIcon }"
                ></Button>
                <h2 class="text-lg font-bold uppercase text-black/60">
                    Skills
                </h2>
                <div>Laravel</div>
                <div>Vue.js</div>
            </div>
            <div
                class="relative container p-6 bg-white rounded shadow-md flex flex-col gap-4 w-full"
            >
                <Button
                    v-if="edit"
                    class="absolute top-0 right-0 m-4"
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
