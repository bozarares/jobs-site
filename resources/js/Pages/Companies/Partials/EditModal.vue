<script setup>
//! Add filepond reset on beforeunload
import { QuillEditor } from '@vueup/vue-quill';
import { ref, defineProps, defineEmits, watch } from 'vue';
import '@vueup/vue-quill/dist/vue-quill.snow.css';
import { onMounted } from 'vue';
import { useForm, usePage } from '@inertiajs/vue3';
import axios from 'axios';

import vueFilePond from 'vue-filepond';
import 'filepond/dist/filepond.min.css';
import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type';
import filePondServer from '@/filePondConfig';

import { Input, SearchInput, Button } from '@/Components/UI';
import {
    AtSymbolIcon,
    GlobeEuropeAfricaIcon,
    MapPinIcon,
    PhoneIcon,
    XMarkIcon,
} from '@heroicons/vue/24/outline';
import { computed } from 'vue';

const props = defineProps({
    company: {
        type: Object,
        required: true,
    },
    show: Boolean,
    type: String,
});

const page = usePage();
const csrfToken = page.props.csrf;

const emit = defineEmits();
function closeModal() {
    emit('update:show', false);
}

const quillRef = ref(null);

const FilePond =
    props.type === 'logo' ? vueFilePond(FilePondPluginFileValidateType) : null;

const filePondRef = ref(null);
const submited = ref(false);

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

const form = useForm({
    country: props.company.country,
    state: props.company.state,
    town: props.company.town,
    address: props.company.address,
    phone_number: props.company.phone_number,
    email: props.company.email,
    description: props.company.description,
    logo: props.company.logo,
    logo_extension: props.company.logo_extension,
});

const submit = () => {
    if (props.type === 'description') {
        const htmlContent = quillRef.value.getHTML();
        form.description = htmlContent;
    }
    if (props.type === 'logo') {
        submited.value = true;
    }
    if (props.type === 'details') {
        form.country = selectedCountry.value || props.company.country;
        form.state = selectedState.value || props.company.state;
        form.town = selectedTown.value || props.company.town;
    }

    form.patch(route('companies.update', { company: props.company.slug }), {
        preserveScroll: true,
        onError: () => {
            submited.value = false;
        },
        onSuccess: () => {
            closeModal();
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
    if (props.type === 'description') {
        quillRef.value.setHTML(props.company.description);
    }
    if (props.type === 'details') {
        getCountries();
    }
});

const onProcessFile = (error, file) => {
    if (error) {
        console.log(error);
        return;
    } else {
        form.logo = file.serverId;
        form.logo_extension = file.fileExtension;
    }
};
</script>

<template>
    <template>
        <Teleport to="body">
            <div
                v-if="show"
                class="fixed inset-0 z-50 flex items-center justify-center"
            >
                <!-- Backdrop -->
                <div
                    @click="closeModal"
                    class="absolute inset-0 bg-black opacity-50"
                ></div>

                <!-- Modal Content -->
                <div
                    class="container relative mx-auto flex max-h-[35em] max-w-lg flex-col gap-8 overflow-auto rounded bg-white p-8 shadow"
                >
                    <h2 class="text-lg font-bold uppercase text-black/60">
                        Edit {{ type }}
                    </h2>
                    <template v-if="type === 'description'">
                        <div class="overflow-auto">
                            <QuillEditor
                                name="Company description"
                                ref="quillRef"
                                toolbar="essential"
                                theme="snow"
                            />
                        </div>
                    </template>
                    <template v-if="type === 'logo'">
                        <div class="overflow-auto">
                            <FilePond
                                id="logo-upload"
                                @processfile="onProcessFile"
                                :server="
                                    filePondServer(
                                        csrfToken,
                                        form.logo,
                                        form.logo_extension,
                                    )
                                "
                                ref="filePondRef"
                                class="w-full"
                                label-idle="Drop the company logo here..."
                                accepted-file-types="image/jpeg, image/png"
                            />
                        </div>
                    </template>
                    <template v-if="type === 'details'">
                        <div class="flex flex-col gap-4 overflow-auto">
                            <Input
                                name="Phone number"
                                v-model="form.phone_number"
                                label="Contact phone number"
                                :error="form.errors.phone_number"
                                :options="{ leftIcon: PhoneIcon }"
                            />
                            <Input
                                name="Contact email"
                                v-model="form.email"
                                label="Contact email"
                                :error="form.errors.email"
                                :options="{ leftIcon: AtSymbolIcon }"
                            />
                            <SearchInput
                                v-model="selectedCountry"
                                label="Country"
                                type="text"
                                name="Company country"
                                :options="{
                                    leftIcon: GlobeEuropeAfricaIcon,
                                    borderStyle: 'bordered',
                                    xMarkIcon: XMarkIcon,
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
                                    (newValue) =>
                                        (selectedCountryValid = newValue)
                                "
                            />
                            <SearchInput
                                v-model="selectedState"
                                :disabled="!states.length"
                                label="County/State"
                                type="text"
                                name="Company state"
                                :options="{
                                    leftIcon: GlobeEuropeAfricaIcon,
                                    borderStyle: 'bordered',
                                    xMarkIcon: XMarkIcon,
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
                                    (newValue) =>
                                        (selectedStateValid = newValue)
                                "
                            />
                            <SearchInput
                                v-model="selectedTown"
                                :disabled="!towns.length"
                                label="Town"
                                type="text"
                                name="Company town"
                                :options="{
                                    leftIcon: GlobeEuropeAfricaIcon,
                                    borderStyle: 'bordered',
                                    xMarkIcon: XMarkIcon,
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
                                :options="{ leftIcon: MapPinIcon }"
                                v-model="form.address"
                                label="Address"
                                :error="form.errors.address"
                            />
                        </div>
                    </template>

                    <Button
                        @click="submit"
                        class=""
                        :options="{ color: 'green', shape: 'pill' }"
                        >Save</Button
                    >
                </div>
            </div>
        </Teleport>
    </template>
</template>
