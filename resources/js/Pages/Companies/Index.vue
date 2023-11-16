<script setup>
// TODO - Add a box for Company Create same as CompanyCard
import { Head, Link } from '@inertiajs/vue3';
import CompanyCard from './Partials/CompanyCard.vue';
import AddCompany from './Partials/AddCompany.vue';
import Company from '@/Models/Company';

const props = defineProps({
    companies: {
        type: Array,
        required: false,
    },
});
const companyInstances = computed(() => {
    return props.companies.map((companyData) => new Company(companyData));
});
</script>
<template>
    <Head title="Companies" />

    <div
        class="flex w-full flex-col items-center space-y-4 bg-zinc-50 p-6 dark:bg-zinc-800/95"
    >
        <h1
            class="text-center text-2xl font-semibold text-zinc-800 dark:text-zinc-100"
        >
            {{ $t('labels.business.center') }}
        </h1>
    </div>

    <div
        class="flex w-full max-w-screen-lg flex-wrap justify-center gap-2 pt-6"
    >
        <CompanyCard
            :viewButton="true"
            class="cursor-pointer transition duration-300 hover:scale-[1.02] hover:shadow-lg"
            v-for="company in companyInstances"
            :key="company.id"
            :company="company"
        />
        <AddCompany />
    </div>
</template>
