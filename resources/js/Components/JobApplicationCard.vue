<script setup>
import { ref, computed } from 'vue';
import RatingStars from './RatingStars.vue';
import Button from './UI/Button/Button.vue';
import { Link } from '@inertiajs/vue3';
import dayjs from 'dayjs';
import relativeTime from 'dayjs/plugin/relativeTime';
import MessageIcon from './UI/Icons/MessageIcon.vue';
dayjs.extend(relativeTime);
import { useModalStore } from '@/Stores/modalStore';
import { EyeIcon } from '@heroicons/vue/24/outline';

const modalStore = useModalStore();
const featured = ref(false);
const props = defineProps({
    application: {
        type: Object,
        required: true,
    },
});

const job = ref(props.application.job);

const formattedApplicationDate = computed(() => {
    return props.application.created_at
        ? dayjs(props.application.created_at).format('D MMM. YYYY')
        : false;
});
const formattedSeenDate = computed(() => {
    return props.application.seen_at
        ? dayjs(props.application.seen_at).format('D MMM. YYYY')
        : false;
});

const experiences = computed(() => {
    return job.value.levels.join(', ');
});

const isClosed = computed(() => {
    return props.application.status === 'closed';
});
</script>

<template>
    <component
        :is="props.application.job.deleted_at ? 'div' : Link"
        :href="
            props.application.job.deleted_at
                ? null
                : route('jobs.show', { job: props.application.job.slug })
        "
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
            <h2 class="mt-2 text-center text-xl font-bold">
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
                <div v-if="application.status" class="text-sm">
                    {{ $t('labels.status') }} -
                    {{ $t(`statuses.${application.status}`) }}
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
        <!-- <div
            v-if="isClosed"
            class="absolute bottom-0 left-0 flex min-h-[10em] w-full translate-y-[10em] cursor-default items-center justify-center rounded-b-md border-t-2 bg-white px-6 transition-transform duration-300 group-hover:translate-y-0"
        >
            <Button :options="{ shape: 'pill', color: 'blue' }" class="w-full"
                >Message</Button
            >
        </div> -->
        <MessageIcon
            v-if="props.application.message"
            class="absolute right-0 top-0 m-2 h-8 w-8 fill-green-600"
            @click.stop.prevent="
                () => {
                    modalStore.openModal(
                        'applicationMessage',
                        (args = {
                            message: application.message,
                        }),
                    );
                }
            "
        />
    </component>
</template>
