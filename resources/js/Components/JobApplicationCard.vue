<script setup>
import { ref } from 'vue';
import RatingStars from './RatingStars.vue';
import { HandThumbDownIcon, HandThumbUpIcon } from '@heroicons/vue/24/outline';
import Button from './UI/Button/Button.vue';
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import dayjs from 'dayjs';
import relativeTime from 'dayjs/plugin/relativeTime';
dayjs.extend(relativeTime);

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
        class="group container relative mx-5 flex w-full flex-col justify-between overflow-hidden rounded bg-white p-6 shadow-md sm:w-[15em] md:mx-0 md:min-h-[25em]"
        :class="
            featured
                ? 'outline outline-2 outline-yellow-500'
                : 'outline outline-gray-200'
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
                class="h-8 fill-current object-contain text-gray-500"
                src="/images/logo/logo-black.png"
                alt="Logo"
            />
            <div class="text-sm text-gray-500">{{ job.company.name }}</div>
            <RatingStars :rating.number="3.5"></RatingStars>
            <!-- Job Title -->
            <h2 class="mt-2 text-center text-xl font-bold">
                {{ job.title }}
            </h2>
            <h3>{{ experiences }}</h3>
            <div v-if="formattedApplicationDate" class="mt-4 text-sm">
                Applied - {{ formattedApplicationDate }}
            </div>
            <div v-if="application.status" class="mt-2 text-sm">
                Status - {{ application.status }}
            </div>
        </div>

        <!-- Location and Salary -->
        <div class="mt-4 flex items-center justify-between">
            <div class="flex flex-col items-center justify-center text-sm">
                <span class="text-center font-bold uppercase">Location </span>
                {{ job.location }}
            </div>
            <div class="flex flex-col items-center text-sm">
                <span class="font-bold uppercase">Salary</span>
                {{ job.salary ? `$${job.salary}` : 'Confidential' }}
            </div>
        </div>
        <div
            v-if="isClosed"
            class="absolute bottom-0 left-0 flex min-h-[10em] w-full translate-y-[10em] cursor-default items-center justify-center rounded-b-md border-t-2 bg-white px-6 transition-transform duration-300 group-hover:translate-y-0"
        >
            <Button :options="{ shape: 'pill', color: 'green' }" class="w-full"
                >Message</Button
            >
        </div>
    </component>
</template>
