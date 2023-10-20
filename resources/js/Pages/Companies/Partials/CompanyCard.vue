<script setup>
import RatingStars from '@/Components/RatingStars.vue';
import { Button, Input } from '@/Components/UI';
import {
    EyeIcon,
    HeartIcon,
    MapPinIcon,
    PencilSquareIcon,
    ShareIcon,
} from '@heroicons/vue/24/outline';
import { Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import EditModal from './EditModal.vue';

const props = defineProps({
    company: {
        type: Object,
        required: true,
    },
    class: {
        type: String,
        default: '',
    },
});

const showModal = ref(false);
const modalType = ref('');

function openModal(type) {
    modalType.value = type;
    showModal.value = true;
}
</script>
<template>
    <div
        :class="class"
        class="relative container bg-white w-full md:w-[15em] flex flex-col justify-between p-6 rounded shadow-md overflow-hidden group"
    >
        <!-- Logo and Company Name -->
        <Button
            class="absolute top-0 right-0 group-hover:opacity-100 opacity-0 transition-opacity scale-75"
            @click="openModal('logo')"
            :options="{ leftIcon: PencilSquareIcon }"
        ></Button>
        <div class="flex flex-col items-center gap-2 relative">
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
    <EditModal
        v-if="showModal"
        :type="modalType"
        :company="company"
        :show="showModal"
        @update:show="showModal = $event"
    />
</template>
