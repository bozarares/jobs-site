<script setup>
import { Link, router } from '@inertiajs/vue3';
import { useModalStore } from '@/Stores/modalStore';
import Job from '@/Models/Job';
import axios from 'axios';
import { throttle } from 'lodash';
import { useUserStore } from '@/Stores/userStore';

const userStore = useUserStore();
const modalStore = useModalStore();
const featured = ref(false);
const props = defineProps({
    job: {
        type: Job,
        required: true,
    },
});

const liked = ref(props.job.like_status);

const like = throttle(async (like_status) => {
    if (userStore.currentUser.isSet() === false) {
        return;
    }
    const response = await axios.post(route('like'), {
        job_slug: props.job.slug,
        like_status: like_status,
    });
    if (response.status === 200) {
        router.reload({ preserveState: true });
        liked.value = like_status;
    }
}, 500);
</script>

<template>
    <Link
        :href="route('jobs.show', { job: props.job.slug })"
        class="group container relative mx-5 flex w-full flex-col justify-between overflow-hidden rounded bg-white p-6 shadow-md dark:bg-zinc-800 dark:text-zinc-100 sm:w-[15em] md:mx-0 md:min-h-[25em]"
        :class="
            featured
                ? 'outline outline-2 outline-yellow-500'
                : 'outline outline-zinc-200 dark:outline-zinc-700'
        "
    >
        <!-- Logo and Company Name -->
        <div class="mb-4 flex flex-col items-center">
            <img
                @error="
                    (e) => {
                        e.target.src = '/images/logo/broken-image.png';
                    }
                "
                :src="job.company.logoPath()"
                class="h-8 fill-current object-contain text-zinc-500 dark:text-zinc-300"
                src="/images/logo/logo-black.png"
                alt="Logo"
            />
            <div class="text-sm text-zinc-500 dark:text-zinc-300">
                {{ job.company.name }}
            </div>
            <RatingStars :rating.number="3.5"></RatingStars>
            <!-- Job Title -->
            <h2 class="mb-2 mt-8 text-center text-xl font-bold">
                {{ job.title }}
            </h2>
            <h3>{{ job.experiences }}</h3>
            <div class="mt-6 flex flex-col items-center gap-1">
                <div v-if="job.formattedApplicationDate" class="text-sm">
                    {{ $t('jobDates.applied') }} -
                    {{ job.formattedApplicationDate }}
                </div>
                <div
                    v-if="job.formattedSeenDate"
                    class="flex items-center gap-1 text-sm text-blue-500"
                >
                    <Mdi:Eye class="w-4" /> {{ $t('jobDates.seen') }} -
                    {{ job.formattedSeenDate }}
                </div>
                <div v-if="job.getApplication.status" class="text-sm">
                    {{ $t('labels.status') }} -
                    {{ $t(`statuses.${job.getApplication.status}`) }}
                </div>
            </div>
        </div>

        <!-- Location and Salary -->
        <div class="mt-4 flex items-center justify-between">
            <div
                @click.stop.prevent="() => {}"
                class="h-18 bottom-0 left-0 flex cursor-default items-center justify-center bg-white transition-transform duration-300 group-hover:translate-y-0 dark:border-zinc-600 dark:bg-zinc-800 sm:absolute sm:w-full sm:translate-y-24 sm:rounded-b-md sm:border-t-2"
            >
                <div
                    class="flex flex-col items-center justify-between gap-2 sm:flex-row sm:py-6"
                >
                    <Button
                        class="text-zinc-100"
                        :disabled="!!job.getApplication.application_date"
                        @click.stop.prevent="
                            () => {
                                if (userStore.currentUser.isSet())
                                    modalStore.openModal('jobApply', {
                                        job: job,
                                    });
                                else router.visit(route('connect'));
                            }
                        "
                        :options="{
                            shape: 'pill',
                            color: job.getApplication.application_date
                                ? 'gray'
                                : 'blue',
                        }"
                        >{{
                            job.getApplication.application_date
                                ? $t('common.apply.applied')
                                : $t('common.apply.fast')
                        }}</Button
                    >
                    <div class="flex items-center gap-4">
                        <div
                            @click="
                                () => {
                                    if (liked === 1) like(0);
                                    else like(1);
                                }
                            "
                            class="group/ratings h-8 w-8 cursor-pointer rounded-full p-0.5 outline-[2px] outline-zinc-500 transition-all duration-300 ease-in-out hover:scale-110"
                            :class="
                                liked === 1
                                    ? 'bg-green-500'
                                    : 'hover:bg-green-500'
                            "
                        >
                            <Mdi:ThumbUp
                                class="h-full w-full fill-white px-1 transition-all duration-300 ease-in-out group-hover/ratings:scale-110"
                                :class="
                                    liked === 1
                                        ? 'text-zinc-100'
                                        : 'text-zinc-800 group-hover/ratings:text-zinc-100 dark:text-zinc-100'
                                "
                            />
                        </div>
                        <div
                            @click="
                                () => {
                                    if (liked === -1) like(0);
                                    else like(-1);
                                }
                            "
                            class="group/ratings h-8 w-8 cursor-pointer rounded-full p-0.5 outline-[2px] outline-zinc-500 transition-all duration-300 ease-in-out hover:scale-110"
                            :class="
                                liked === -1 ? 'bg-red-500' : 'hover:bg-red-500'
                            "
                        >
                            <Mdi:ThumbDown
                                class="h-full w-full fill-white px-1 transition-all duration-300 ease-in-out group-hover/ratings:scale-110"
                                :class="
                                    liked === -1
                                        ? 'text-zinc-100'
                                        : 'text-zinc-800 group-hover/ratings:text-zinc-100 dark:text-zinc-100'
                                "
                            />
                        </div>
                    </div>
                </div>
            </div>
            <div
                class="flex flex-col items-center justify-between gap-4 sm:w-full sm:flex-row"
            >
                <div class="flex flex-col items-center text-sm">
                    <span class="font-bold uppercase">{{
                        $t('labels.salary')
                    }}</span>
                    {{ job.salary ? `$${job.salary}` : 'Confidential' }}
                </div>
                <div class="flex flex-col items-center justify-center text-sm">
                    <span class="text-center font-bold uppercase"
                        >{{ $t('labels.location') }}
                    </span>
                    {{ job.location }}
                </div>
            </div>
        </div>
    </Link>
</template>
