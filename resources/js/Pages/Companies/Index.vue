<script setup>
// TODO - Add a box for Company Create same as CompanyCard
import { Button } from '@/Components/UI';
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
    <div class="flex w-full flex-col items-center space-y-4 bg-gray-50 p-6">
        <h1 class="text-center text-2xl font-semibold text-gray-800">
            {{ $t('labels.business_center') }}
        </h1>
        <div class="flex flex-col items-center gap-2 md:flex-row md:gap-4">
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
        class="flex w-full max-w-screen-lg flex-wrap justify-center gap-2 bg-gray-100 pt-6"
    >
        <CompanyCard
            :viewButton="true"
            class="cursor-pointer transition duration-300 hover:scale-[1.02] hover:shadow-lg"
            v-for="company in companies"
            :key="company.id"
            :company="company"
        />
    </div>
</template>
