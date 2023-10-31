<script setup>
import { ref } from 'vue';
import RatingStars from './RatingStars.vue';
import { HandThumbDownIcon, HandThumbUpIcon } from '@heroicons/vue/24/outline';
import Button from './UI/Button/Button.vue';
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';
const featured = ref(false);
const props = defineProps({
    job: {
        type: Object,
        required: true,
    },
});

const experiences = computed(() => {
    return props.job.levels.join(', ');
});
</script>

<template>
    <Link
        :href="route('jobs.show', { job: props.job.slug })"
        class="group container relative mx-5 flex w-full flex-col justify-between overflow-hidden rounded bg-white p-6 shadow-md sm:w-[15em] md:mx-0 md:min-h-[20em]"
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
            <h2 class="mb-2 mt-8 text-center text-xl font-bold">
                {{ job.title }}
            </h2>
            <h3>{{ experiences }}</h3>
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
            @click.stop.prevent="() => {}"
            class="h-18 absolute bottom-0 left-0 flex w-full translate-y-24 cursor-default items-center justify-center rounded-b-md border-t-2 bg-white transition-transform duration-300 group-hover:translate-y-0"
        >
            <div class="flex items-center justify-center gap-4">
                <Button
                    @click.stop.prevent="() => {}"
                    :options="{ shape: 'pill', color: 'green' }"
                    >Fast Apply</Button
                >
                <div class="flex h-20 w-20 items-center gap-4">
                    <div
                        class="group/ratings h-8 w-8 cursor-pointer rounded-full p-0.5 outline outline-gray-500 transition-all duration-150 ease-in-out hover:scale-110 hover:bg-red-500/50"
                    >
                        <HandThumbDownIcon
                            class="fill-white transition-all duration-500 ease-in-out group-hover/ratings:scale-110"
                        />
                    </div>
                    <div
                        class="group/ratings h-8 w-8 cursor-pointer rounded-full p-0.5 outline outline-gray-500 transition-all duration-150 ease-in-out hover:scale-110 hover:bg-green-500/50"
                    >
                        <HandThumbUpIcon
                            class="fill-white transition-all duration-500 ease-in-out group-hover/ratings:scale-110"
                        />
                    </div>
                </div>
            </div>
        </div>
    </Link>
</template>
