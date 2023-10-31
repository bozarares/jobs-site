<script setup>
import RatingStars from '@/Components/RatingStars.vue';
import { Button } from '@/Components/UI';
import {
    EyeIcon,
    HeartIcon,
    MapPinIcon,
    ShareIcon,
} from '@heroicons/vue/24/outline';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    job: {
        type: Object,
        required: true,
    },
    class: {
        type: String,
        default: '',
    },
    edit: {
        type: Boolean,
        default: false,
    },
    openModal: {
        type: Function,
        default: () => {},
    },
});

console.log(props.job);
</script>
<template>
    <div
        :id="`job-card-${job.title}`"
        :class="class"
        class="group container relative flex w-full flex-col justify-between overflow-hidden rounded bg-white p-6 shadow-md md:w-[15em]"
    >
        <!-- Logo and Company Name -->

        <div class="relative flex flex-col items-center gap-2">
            <Link
                class="flex w-full flex-col gap-1"
                :href="
                    route('companies.show', { company: props.job.company.slug })
                "
            >
                <img
                    @error="
                        (e) => {
                            e.target.src = '/images/logo/broken-image.png';
                        }
                    "
                    class="h-12 fill-current object-contain text-gray-500"
                    :src="
                        '/logos/companies/' +
                        job.company.logo +
                        '.' +
                        job.company.logo_extension
                    "
                    alt="Logo"
                />
                <h2 class="mb-2 text-center text-xs font-bold uppercase">
                    <!-- Job Title -->
                    {{ job.company.name }}
                </h2>
            </Link>
            <h2 class="mb-2 text-center font-bold uppercase">
                {{ job.title }}
            </h2>
            <RatingStars :rating.number="3.5"></RatingStars>
            <div class="flex flex-col items-center gap-2">
                <div class="flex items-center gap-2 text-sm">
                    <span class="font-bold uppercase">Salary</span>
                    {{ job.salary ? `$${job.salary}` : 'Confidential' }}
                </div>
                <div class="flex items-center gap-2 text-sm">
                    <span class="font-bold uppercase">Town</span>
                    {{ job.location }}
                </div>
                <div class="flex items-center gap-2 text-sm">
                    <span class="font-bold uppercase">Type</span>
                    {{ job.type }}
                </div>
            </div>
            <div
                class="mt-3 flex w-[10em] flex-col items-center gap-3 md:w-full"
            >
                <Button
                    @click="
                        () => {
                            openModal('apply');
                        }
                    "
                    class="w-full"
                    :options="{
                        shape: 'pill',
                        color: 'green',
                        // leftIcon: ShareIcon,
                    }"
                    >Apply</Button
                >
            </div>
        </div>
    </div>
</template>
