<script setup>
import { Button, Input } from '@/Components/UI';
import { Link } from '@inertiajs/vue3';
import CompanyCard from './Partials/CompanyCard.vue';

const props = defineProps({
    companies: {
        type: Array,
        required: false,
    },
});
</script>
<template>
    <div class="flex flex-col items-center w-full space-y-4 bg-gray-50 p-6">
        <h1 class="text-2xl font-semibold text-gray-800 text-center">
            Businesses Center
        </h1>
        <div class="flex md:flex-row flex-col items-center gap-2 md:gap-4">
            <div class="flex gap-2">
                <Button
                    as="a"
                    :is="Link"
                    :href="route('companies.create')"
                    :options="{ shape: 'pill' }"
                    >Create a new business</Button
                >
            </div>
        </div>
    </div>
    <div
        class="flex gap-2 flex-wrap justify-center w-full p-6 bg-gray-100 max-w-screen-lg"
    >
        <CompanyCard
            class="transition hover:scale-[1.02] duration-300 hover:shadow-lg cursor-pointer"
            v-for="company in companies"
            :key="company.id"
            :company="company"
            view-button="true"
        />
    </div>
</template>
