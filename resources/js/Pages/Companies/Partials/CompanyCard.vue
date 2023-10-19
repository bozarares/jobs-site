<script setup>
import RatingStars from '@/Components/RatingStars.vue';
import { Button, Input } from '@/Components/UI';
import {
    EyeIcon,
    HeartIcon,
    MapPinIcon,
    ShareIcon,
} from '@heroicons/vue/24/outline';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    company: {
        type: Object,
        required: true,
    },
    class: {
        type: String,
        default: '',
    },
    viewButton: {
        type: Boolean,
        default: false,
    },
});
</script>
<template>
    <div
        :class="class"
        class="group relative container bg-white w-full md:w-[15em] flex flex-col justify-between p-6 rounded shadow-md overflow-hidden"
    >
        <!-- Logo and Company Name -->
        <div class="flex flex-col items-center gap-2">
            <img
                @error="
                    (e) => {
                        e.target.src = '/images/logo/broken-image.png';
                    }
                "
                class="h-12 fill-current object-contain text-gray-500"
                :src="'/logos/companies/' + company.logo"
                alt="Logo"
            />

            <!-- Job Title -->
            <h2 class="text-lg uppercase font-bold mb-2 text-center">
                {{ company.name }}
            </h2>
            <div class="flex flex-col items-center">
                <h2
                    class="text-sm flex flex-row gap-1 items-center font-semibold text-center"
                >
                    <span class="w-4 h-4"><MapPinIcon /></span
                    >{{ company.state }},
                    {{ company.country }}
                </h2>
                <h2
                    class="text-sm flex flex-row gap-1 items-center font-semibold mb-2 text-center"
                >
                    {{ company.town }}
                </h2>
            </div>
            <RatingStars :rating.number="3.5"></RatingStars>
            <div
                class="flex flex-col items-center gap-3 mt-3 w-[10em] md:w-full"
            >
                <Button
                    class="w-full"
                    :options="{
                        shape: 'pill',
                        color: 'green',
                        leftIcon: ShareIcon,
                    }"
                    >Share</Button
                >
                <Button
                    v-if="viewButton"
                    class="w-full"
                    as="a"
                    :is="Link"
                    :href="route('companies.show', company.id)"
                    :options="{
                        shape: 'pill',
                        color: 'green',
                        leftIcon: EyeIcon,
                    }"
                    >View</Button
                >
                <Button
                    class="w-full"
                    :options="{
                        shape: 'pill',
                        color: 'green',
                        leftIcon: HeartIcon,
                    }"
                    >Follow</Button
                >
            </div>
        </div>
    </div>
</template>
