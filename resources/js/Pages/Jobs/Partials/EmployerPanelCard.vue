<script setup>
import { Button, Input } from '@/Components/UI';
import { ChevronLeftIcon, ChevronRightIcon } from '@heroicons/vue/24/outline';
import axios from 'axios';
import { computed, watch, ref } from 'vue';

const props = defineProps({
    application: Object,
    clickApplication: { type: Function, default: () => {} },
});

const applications = ref([]);
const job = ref(props.application ? props.application.job : null); // Initialize the job ref
const scrollContainer = ref(null);
const isLoaded = ref(false);
const currentPage = ref(1);
const lastPage = ref(null);
const isLastPage = computed(() => currentPage.value > lastPage.value);
const isCardVisible = ref(false);
const applicationStatus = ref('open');
const message = ref('');

// Helper method to reset application loading
const resetApplicationLoading = () => {
    isLoaded.value = false;
    applications.value = [];
    currentPage.value = 1;
};

const loadApplications = async (page = 1) => {
    if (isLoaded.value || !job.value) {
        return;
    }

    try {
        const response = await axios.post(
            route('job.load.application', { job: job.value.slug }),
            { page: page, status: applicationStatus.value },
        );
        currentPage.value++;

        lastPage.value = response.data.lastPage;
        applications.value.push(...response.data.applications);
        isLoaded.value = true;
    } catch (error) {
        console.log(isLoaded.value);
        console.error('There was an error fetching the applications:', error);
    }
};

const setStatus = async (status) => {
    try {
        const response = await axios.patch(
            route('application.set.status', {
                job: job.value.slug,
                application: props.application.id,
            }),
            { status: status, message: message.value },
        );
        applications.value = applications.value.filter(
            (application) => application.id !== props.application.id,
        );
        message.value = '';
        if (applications.value[0]) {
            props.clickApplication(applications.value[0].id);
        }
    } catch {
        console.error(
            'There was an error setting the application status:',
            error,
        );
    }
};

// Load more applications as the user scrolls
const loadMoreApplications = async () => {
    isLoaded.value = false;
    await loadApplications(currentPage.value);
};

// Handle the scrolling to load more applications
const handleScroll = () => {
    const container = scrollContainer.value;
    if (container) {
        const buffer = 500;
        const atBottomOrNearBottom =
            container.scrollHeight - container.scrollTop - buffer <=
            container.clientHeight;
        if (atBottomOrNearBottom && !isLastPage.value && isLoaded.value) {
            loadMoreApplications();
        }
    }
};

// Watch for changes in the application prop, especially for changes in the job
watch(
    () => props.application,
    (newApplication, oldApplication) => {
        if (newApplication && newApplication.job && isLoaded.value === false) {
            job.value = newApplication.job; // Update the job ref
            resetApplicationLoading(); // Reset loading state
            loadApplications(); // Load applications for the new job
        }
    },
    { immediate: true },
);

// Watch for changes in application status and reload applications accordingly
watch(applicationStatus, () => {
    resetApplicationLoading();
    loadApplications();
});
</script>

<template>
    <div
        @click="
            () => {
                isCardVisible = !isCardVisible;
            }
        "
        class="fixed bottom-0 z-[100] flex w-full flex-col items-center justify-center bg-white py-2 md:hidden"
    >
        <ChevronRightIcon v-if="isCardVisible" class="w-6" />
        <ChevronLeftIcon v-if="!isCardVisible" class="w-6" />
        <div>{{ isCardVisible ? 'Hide' : 'Show' }} Employer Options</div>
    </div>
    <div
        class="container absolute z-[100] m-2 flex max-h-[35em] w-full flex-col overflow-hidden rounded bg-white py-8 shadow transition-all duration-300 ease-in-out md:relative md:z-50 md:block md:max-w-sm"
        :class="{
            'translate-x-[150%] md:translate-x-0': !isCardVisible,
            'translate-x-0': isCardVisible,
        }"
    >
        <div class="h-full">
            <h2 class="px-4 text-lg font-bold uppercase text-black/60">
                Employer Options
            </h2>
            <div class="flex justify-around border-t-[1px]">
                <div
                    class="h-full w-full border-r-[1px] py-3 text-center"
                    :class="{
                        'bg-gray-700 text-white': applicationStatus === 'open',
                    }"
                    @click="
                        () => {
                            if (applicationStatus !== 'open')
                                applicationStatus = 'open';
                        }
                    "
                >
                    Open
                </div>
                <div
                    @click="
                        () => {
                            if (applicationStatus !== 'accepted')
                                applicationStatus = 'accepted';
                        }
                    "
                    class="h-full w-full border-r-[1px] py-3 text-center"
                    :class="{
                        'bg-gray-700 text-white':
                            applicationStatus === 'accepted',
                    }"
                >
                    Accepted
                </div>
            </div>
            <div
                class="flex h-[18em] flex-col overflow-scroll"
                ref="scrollContainer"
                @scroll="handleScroll"
            >
                <div
                    class="relative flex cursor-pointer items-center justify-center border-b-[1px] border-gray-200 py-4 first:border-t-[1px]"
                    v-for="application in applications"
                    @click="
                        () => {
                            if (application.id !== props.application.id) {
                                isCardVisible = false;
                                message = '';
                                clickApplication(application.id);
                            }
                        }
                    "
                >
                    <ChevronRightIcon
                        v-if="application.id === props.application.id"
                        class="absolute left-0 h-6 self-start pl-6 text-green-600"
                    />
                    <div
                        class="text-lg font-bold"
                        :class="{
                            'text-green-600':
                                application.id === props.application.id,
                        }"
                    >
                        {{ application.userName }}
                    </div>
                </div>
            </div>
            <div
                v-if="applicationStatus === 'open'"
                class="flex h-[10em] flex-col items-center justify-center gap-4"
            >
                <Input class="px-2" label="Message" v-model="message" />
                <div class="flex items-center justify-center gap-1">
                    <Button
                        @click="
                            () => {
                                setStatus('accepted');
                            }
                        "
                        class="rounded-r-none"
                        :options="{ color: 'green', shape: 'pill' }"
                        >Accept</Button
                    >
                    <Button
                        @click="
                            () => {
                                setStatus('closed');
                            }
                        "
                        class="rounded-l-none"
                        :options="{ color: 'red', shape: 'pill' }"
                        >Decline</Button
                    >
                </div>
            </div>
            <div
                v-else
                class="flex h-[10em] flex-col items-center justify-center gap-4"
            >
                <Input class="px-2" label="Message" v-model="message" />
                <div class="flex items-center justify-center gap-1">
                    <Button
                        @click="
                            () => {
                                setStatus('hired');
                            }
                        "
                        class="rounded-r-none"
                        :options="{ color: 'green', shape: 'pill' }"
                        >Hire</Button
                    >
                    <Button
                        @click="
                            () => {
                                setStatus('closed');
                            }
                        "
                        class="rounded-l-none"
                        :options="{ color: 'red', shape: 'pill' }"
                        >Decline</Button
                    >
                </div>
            </div>
        </div>
    </div>
</template>
