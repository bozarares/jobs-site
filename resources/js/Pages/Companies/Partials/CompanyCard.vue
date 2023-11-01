<script setup>
import RatingStars from '@/Components/RatingStars.vue';
import { Button } from '@/Components/UI';
import { useModalStore } from '@/Stores/modalStore';
import {
    EyeIcon,
    HeartIcon,
    MapPinIcon,
    ShareIcon,
} from '@heroicons/vue/24/outline';
import { Link } from '@inertiajs/vue3';

const modalStore = useModalStore();

const props = defineProps({
    company: {
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
    viewButton: {
        type: Boolean,
        default: false,
    },
});
</script>
<template>
    <div
        :id="`company-card-${company.name}`"
        :class="class"
        class="group container relative flex w-full flex-col justify-between overflow-hidden rounded bg-white p-6 shadow-md md:w-[15em]"
    >
        <!-- Logo and Company Name -->

        <div class="relative flex flex-col items-center gap-2">
            <img
                :id="edit ? 'company-logo-edit' : ''"
                @click="
                    () => {
                        if (edit) {
                            modalStore.openModal('companyLogo');
                        }
                    }
                "
                @error="
                    (e) => {
                        e.target.src = '/images/logo/broken-image.png';
                    }
                "
                :class="{
                    'cursor-pointer': edit,
                }"
                class="h-12 fill-current object-contain text-gray-500"
                :src="
                    '/logos/companies/' +
                    company.logo +
                    '.' +
                    company.logo_extension
                "
                alt="Logo"
            />
            <h2
                v-if="edit"
                class="pr-2 font-extrabold text-gray-500 transition-all duration-150 ease-in-out group-hover:text-black"
            >
                Click logo to edit
            </h2>
            <!-- Job Title -->
            <h2 class="mb-2 text-center text-lg font-bold uppercase">
                {{ company.name }}
            </h2>
            <div class="flex flex-col items-center">
                <h2
                    class="flex flex-row items-center gap-1 text-center text-sm font-semibold"
                >
                    <span class="h-4 w-4"><MapPinIcon /></span
                    >{{ company.state }},
                    {{ company.country }}
                </h2>
                <h2
                    class="mb-2 flex flex-row items-center gap-1 text-center text-sm font-semibold"
                >
                    {{ company.town }}
                </h2>
            </div>
            <RatingStars :rating.number="3.5"></RatingStars>
            <div
                class="mt-3 flex w-[10em] flex-col items-center gap-3 md:w-full"
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
                    :id="`company-view-${company.name}`"
                    v-if="viewButton"
                    class="w-full"
                    as="a"
                    :is="Link"
                    :href="route('companies.show', company.slug)"
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
