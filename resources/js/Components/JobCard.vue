<script setup>
import { ref, computed } from 'vue';
import RatingStars from './RatingStars.vue';
import {
    EyeIcon,
    HandThumbDownIcon,
    HandThumbUpIcon,
} from '@heroicons/vue/24/outline';
import Button from './UI/Button/Button.vue';
import { Link } from '@inertiajs/vue3';
import dayjs from 'dayjs';
import relativeTime from 'dayjs/plugin/relativeTime';
dayjs.extend(relativeTime);

const featured = ref(false);
const props = defineProps({
    job: {
        type: Object,
        required: true,
    },
});

const formattedApplicationDate = computed(() => {
    return props.job.application_date
        ? dayjs(props.job.application_date).format('D MMM. YYYY')
        : false;
});

const formattedSeenDate = computed(() => {
    return props.job.seen_at
        ? dayjs(props.seen_at).format('D MMM. YYYY')
        : false;
});

const experiences = computed(() => {
    return props.job.levels.join(', ');
});
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
                :src="
                    '/logos/companies/' +
                    job.company.logo +
                    '.' +
                    job.company.logo_extension
                "
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
            <h3>{{ experiences }}</h3>
            <div class="mt-6 flex flex-col items-center gap-1">
                <div v-if="formattedApplicationDate" class="text-sm">
                    {{ $t('jobDates.applied') }} -
                    {{ formattedApplicationDate }}
                </div>
                <div
                    v-if="formattedSeenDate"
                    class="flex items-center gap-1 text-sm text-blue-500"
                >
                    <EyeIcon class="w-4" /> {{ $t('jobDates.seen') }} -
                    {{ formattedSeenDate }}
                </div>
                <div v-if="job.status" class="text-sm">
                    {{ $t('labels.status') }} -
                    {{ $t(`statuses.${job.status}`) }}
                </div>
            </div>
        </div>

        <!-- Location and Salary -->
        <div class="mt-4 flex items-center justify-between">
            <div class="flex flex-col items-center justify-center text-sm">
                <span class="text-center font-bold uppercase"
                    >{{ $t('labels.location') }}
                </span>
                {{ job.location }}
            </div>
            <div class="flex flex-col items-center text-sm">
                <span class="font-bold uppercase">{{
                    $t('labels.salary')
                }}</span>
                {{ job.salary ? `$${job.salary}` : 'Confidential' }}
            </div>
        </div>
        <div
            @click.stop.prevent="() => {}"
            class="h-18 absolute bottom-0 left-0 flex w-full translate-y-24 cursor-default items-center justify-center rounded-b-md border-t-2 bg-inherit transition-transform duration-300 group-hover:translate-y-0 dark:border-zinc-600"
        >
            <div class="flex items-center justify-center gap-4">
                <Button
                    @click.stop.prevent="() => {}"
                    :options="{ shape: 'pill', color: 'blue' }"
                    >Fast Apply</Button
                >
                <div class="flex h-20 w-20 items-center gap-4">
                    <div
                        class="group/ratings h-8 w-8 cursor-pointer rounded-full p-0.5 outline outline-zinc-500 transition-all duration-150 ease-in-out hover:scale-110 hover:bg-red-500/50"
                    >
                        <HandThumbDownIcon
                            class="fill-white text-black transition-all duration-500 ease-in-out group-hover/ratings:scale-110"
                        />
                    </div>
                    <div
                        class="group/ratings h-8 w-8 cursor-pointer rounded-full p-0.5 outline outline-zinc-500 transition-all duration-150 ease-in-out hover:scale-110 hover:bg-green-500/50"
                    >
                        <HandThumbUpIcon
                            class="fill-white text-black transition-all duration-500 ease-in-out group-hover/ratings:scale-110"
                        />
                    </div>
                </div>
            </div>
        </div>
    </Link>
</template>
