<script setup>
import { useForm, usePage } from '@inertiajs/vue3';

import mdiAt from '~icons/mdi/at';
import mdiPhone from '~icons/mdi/phone';
import mdiEarth from '~icons/mdi/earth';
import mdiMapMarker from '~icons/mdi/map-marker';

import heroiconsXMark from '~icons/heroicons/x-mark';

import axios from 'axios';

const props = defineProps({
    closeModal: { type: Function, default: () => {} },
});
const countries = ref([]);
const states = ref([]);
const towns = ref([]);

const selectedCountry = ref('');
const selectedState = ref('');
const selectedTown = ref('');

const selectedCountryValid = ref(false);
const selectedStateValid = ref(false);
const selectedTownValid = ref(false);

const addressValid = computed(() => {
    return (
        selectedCountryValid.value &&
        selectedStateValid.value &&
        selectedTownValid.value
    );
});

const page = usePage();
const company = page.props.company;

const form = useForm({
    phone_number: company.contact.phone_number,
    email: company.contact.email,
    country: company.location.country,
    state: company.location.state,
    town: company.location.town,
    address: company.location.address,
});

const submit = () => {
    if (!addressValid.value) return;
    form.country = selectedCountry.value || props.company.country;
    form.state = selectedState.value || props.company.state;
    form.town = selectedTown.value || props.company.town;
    form.patch(route('companies.update.contact', { company: company.slug }), {
        onFinish: () => {
            props.closeModal();
        },
    });
};

const getCountries = async () => {
    try {
        const response = await axios.get(
            'https://countriesnow.space/api/v0.1/countries/flag/unicode',
        );
        countries.value = response.data.data;
    } catch (error) {
        console.log('error', error);
    }
};

const getStates = async () => {
    try {
        const response = await axios.post(
            'https://countriesnow.space/api/v0.1/countries/states',
            { country: selectedCountry.value },
            { headers: { 'Content-Type': 'application/json' } },
        );
        states.value = response.data.data.states;
    } catch (error) {
        console.log('error', error);
    }
};

const getTowns = async () => {
    try {
        const response = await axios.post(
            'https://countriesnow.space/api/v0.1/countries/state/cities',
            { country: selectedCountry.value, state: selectedState.value },
            { headers: { 'Content-Type': 'application/json' } },
        );
        towns.value = response.data.data;
    } catch (error) {
        console.log('error', error);
    }
};

watch(selectedCountryValid, (newValue) => {
    if (newValue) {
        getStates();
    } else {
        states.value = [];
        towns.value = [];
        selectedState.value = '';
        selectedTown.value = '';
    }
});

watch(selectedStateValid, (newValue) => {
    if (newValue) {
        getTowns();
    } else {
        towns.value = [];
        selectedTown.value = '';
    }
});

onMounted(() => {
    getCountries();
});
</script>

<template>
    <div
        class="container relative mx-auto flex max-h-[35em] max-w-lg flex-col gap-8 rounded bg-white p-8 shadow dark:bg-zinc-800 dark:text-zinc-100"
    >
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-bold uppercase">
                {{ $t('labels.contact.edit') }}
            </h2>
            <Heroicons:xMark class="h-6 cursor-pointer" @click="closeModal()" />
        </div>

        <div class="flex flex-col gap-4 overflow-auto">
            <Input
                name="Phone number"
                v-model="form.phone_number"
                :label="$t('labels.phone.contact')"
                :error="form.errors.phone_number"
                :options="{
                    size: 'small',
                    borderStyle: 'border-bottom',
                    leftIcon: mdiPhone,
                }"
            />
            <Input
                name="Contact email"
                v-model="form.email"
                :label="$t('labels.email.contact')"
                :error="form.errors.email"
                :options="{
                    size: 'small',
                    borderStyle: 'border-bottom',
                    leftIcon: mdiAt,
                }"
            />
            <SearchInput
                v-model="selectedCountry"
                :label="$t('labels.country.self')"
                type="text"
                name="Company country"
                :options="{
                    size: 'small',
                    leftIcon: mdiEarth,
                    borderStyle: 'border-bottom',
                    xMarkIcon: heroiconsXMark,
                    noResultMessage: 'No results found! 😢',
                }"
                :search="{
                    items: countries,
                    searchField: 'name',
                    keys: ['name', 'iso2', 'iso3'],
                    maxResults: 20,
                    hotReload: true,
                }"
                :validity-check="true"
                :on-validity-change="
                    (newValue) => (selectedCountryValid = newValue)
                "
            />
            <SearchInput
                v-model="selectedState"
                :disabled="!states.length"
                :label="$t('labels.country.county')"
                type="text"
                name="Company state"
                :options="{
                    size: 'small',
                    leftIcon: mdiEarth,
                    borderStyle: 'border-bottom',
                    xMarkIcon: heroiconsXMark,
                    noResultMessage: 'No results found! 😢',
                }"
                :search="{
                    items: states,
                    searchField: 'name',
                    keys: ['name'],
                    maxResults: 20,
                    hotReload: true,
                }"
                :validity-check="true"
                :on-validity-change="
                    (newValue) => (selectedStateValid = newValue)
                "
            />
            <SearchInput
                v-model="selectedTown"
                :disabled="!towns.length"
                :label="$t('labels.country.town')"
                type="text"
                name="Company town"
                :options="{
                    size: 'small',
                    leftIcon: mdiEarth,
                    borderStyle: 'border-bottom',
                    xMarkIcon: heroiconsXMark,
                    noResultMessage: 'No results found! 😢',
                }"
                :search="{
                    items: towns,
                    searchField: 'name',
                    keys: ['name'],
                    maxResults: 20,
                    hotReload: true,
                }"
                :validity-check="true"
                :on-validity-change="
                    (newValue) => (selectedTownValid = newValue)
                "
            />
            <Input
                name="Company address"
                :disabled="!addressValid"
                :options="{
                    size: 'small',
                    borderStyle: 'border-bottom',
                    leftIcon: mdiMapMarker,
                }"
                v-model="form.address"
                :label="$t('labels.address')"
                :error="form.errors.address"
            />
        </div>
        <Button
            @click="submit"
            class=""
            :options="{
                size: 'small',
                color: 'blue',
                shape: 'pill',
            }"
            >{{ $t('common.save') }}</Button
        >
    </div>
</template>

<style></style>
