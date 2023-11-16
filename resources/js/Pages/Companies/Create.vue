<script setup>
import { Button, Input, SearchInput } from '@/Components/UI';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import filePondServer from '@/filePondConfig';
import axios from 'axios';

import 'filepond/dist/filepond.min.css';
import 'filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css';

import mdiAt from '~icons/mdi/at';
import mdiEarth from '~icons/mdi/earth';
import mdiMapMarker from '~icons/mdi/map-marker';
import mdiPhone from '~icons/mdi/phone';
import mdiOfficeBuilding from '~icons/mdi/office-building';
import mdiOfficeBuildingCog from '~icons/mdi/office-building-cog';

import heroiconsXMark from '~icons/heroicons/x-mark';

const beforeUnload = () => {
    revertFiles();
};

const quillRef = ref(null);
const isClient = ref(false);

onBeforeMount(() => {
    isClient.value = typeof window !== 'undefined';
});

let QuillEditor = null;
let FilePond = null;

onMounted(async () => {
    if (isClient.value) {
        // Dynamic imports for QuillEditor
        const { QuillEditor: QuillImport } = await import('@vueup/vue-quill');
        QuillEditor = QuillImport;

        const { default: VueFilePond } = await import('vue-filepond');
        const FilePondPluginFileValidateType = await import(
            'filepond-plugin-file-validate-type'
        );
        const FilePondPluginImagePreview = await import(
            'filepond-plugin-image-preview'
        );

        FilePond = VueFilePond(
            FilePondPluginFileValidateType.default,
            FilePondPluginImagePreview.default,
        );
    }
    window.addEventListener('beforeunload', beforeUnload);
});
onBeforeUnmount(() => {
    revertFiles();
    window.removeEventListener('beforeunload', beforeUnload);
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

watch(
    () => selectedCountry.value,
    (_1, _2) => {
        states.value = [];
        towns.value = [];
        selectedState.value = '';
        selectedTown.value = '';
    },
);

watch(
    () => selectedState.value,
    (_1, _2) => {
        towns.value = [];
        selectedTown.value = '';
    },
);

axios
    .get('https://countriesnow.space/api/v0.1/countries/flag/unicode')
    .then((response) => {
        countries.value = response.data.data;
    })
    .catch((error) => {
        console.log('error', error);
    });

const getStates = () => {
    axios
        .post(
            'https://countriesnow.space/api/v0.1/countries/states',
            { country: selectedCountry.value },
            {
                headers: {
                    'Content-Type': 'application/json',
                },
            },
        )
        .then((response) => {
            states.value = response.data.data.states;
        })
        .catch((error) => {
            console.log('error', error);
        });
};

const getTowns = () => {
    axios
        .post(
            'https://countriesnow.space/api/v0.1/countries/state/cities',
            { country: selectedCountry.value, state: selectedState.value },
            {
                headers: {
                    'Content-Type': 'application/json',
                },
            },
        )
        .then((response) => {
            towns.value = response.data.data;
        })
        .catch((error) => {
            console.log('error', error);
        });
};

// Create component

const page = usePage();
const csrfToken = page.props.csrf;

const filePondRef = ref(null);

const submited = ref(false);
const uploaded = ref(false);

const form = useForm({
    name: '',
    code: '',
    country: '',
    state: '',
    town: '',
    address: '',
    phone_number: '',
    email: '',
    description: '',
    logo: null,
    logo_extension: null,
});

watch(filePondRef, (newValue, oldValue) => {
    const files = newValue.getFiles();
    if (files.length > 0) {
        form.logo = files[0].file;
    }
});

const submit = () => {
    const htmlContent = quillRef.value.getHTML();
    form.description = htmlContent;
    form.country = selectedCountry.value;
    form.state = selectedState.value;
    form.town = selectedTown.value;
    form.post(route('companies.store'), {
        onStart: () => {
            uploaded.value = false;
            submited.value = true;
        },
        onError: () => {
            submited.value = false;
        },
        onFinish: () => {
            form.reset('name', 'code', 'location', 'description');
        },
    });
};

watch(
    () => submited.value,
    (newValue, oldValue) => {
        console.log(newValue);
    },
);

onBeforeUnmount(() => {
    if (submited.value === false) {
        if (filePondRef.value) {
            const files = filePondRef.value.getFiles();
            if (files.length > 0) {
                filePondRef.value.removeFiles(files[0]);
            }
        }
    }
});

const onProcessFile = (error, file) => {
    if (error) {
        console.log(error);
        return;
    } else {
        uploaded.value = true;
        form.logo = file.serverId;
        form.logo_extension = file.fileExtension;
    }
};

const revertFiles = () => {
    if (uploaded.value === true && filePondRef.value) {
        const filepondFile = filePondRef.value.getFile();
        if (filepondFile) {
            axios.delete(route('uploads.destroy'), {
                data: {
                    filename: filepondFile.serverId,
                    extension: filepondFile.fileExtension,
                },
            });
            filePondRef.value.removeFile(filepondFile);
        }
    }
};
</script>

<template>
    <Head title="Create" />

    <div
        class="container mt-12 flex max-w-screen-md flex-col items-center rounded-md bg-white p-4 shadow-md dark:bg-zinc-800 dark:text-zinc-100"
    >
        <h2 class="text-center text-2xl font-bold">
            {{ $t('messages.thanks') }}
        </h2>
        <h3
            class="mb-4 text-center text-lg font-semibold text-zinc-700 dark:text-zinc-200"
        >
            {{ $t('messages.companyCreate') }}
        </h3>
        <div class="flex w-full max-w-lg flex-col items-center gap-4">
            <div class="flex w-full flex-col gap-4 md:flex-row">
                <Input
                    name="Company name"
                    v-model="form.name"
                    :label="$t('labels.companyDetails.name')"
                    :error="form.errors.name"
                    :options="{
                        leftIcon: mdiOfficeBuilding,

                        borderStyle: 'border-bottom',
                    }"
                />
                <Input
                    name="Company code"
                    v-model="form.code"
                    :label="$t('labels.companyDetails.registrationCode')"
                    :error="form.errors.code"
                    :options="{
                        leftIcon: mdiOfficeBuildingCog,

                        borderStyle: 'border-bottom',
                    }"
                />
            </div>
            <div class="flex w-full flex-col gap-4 md:flex-row">
                <Input
                    name="Phone number"
                    v-model="form.phone_number"
                    :label="$t('labels.phone.contact')"
                    :error="form.errors.phone_number"
                    :options="{
                        leftIcon: mdiPhone,

                        borderStyle: 'border-bottom',
                    }"
                />
                <Input
                    name="Contact email"
                    v-model="form.email"
                    :label="$t('labels.email.contact')"
                    :error="form.errors.email"
                    :options="{
                        leftIcon: mdiAt,

                        borderStyle: 'border-bottom',
                    }"
                />
            </div>
            <div class="flex w-full flex-col gap-4 md:flex-row">
                <SearchInput
                    v-model="selectedCountry"
                    :label="$t('labels.country.self')"
                    type="text"
                    name="Company country"
                    :options="{
                        leftIcon: mdiEarth,
                        borderStyle: 'bordered',
                        heroiconsXMark: heroiconsXMark,
                        noResultMessage: 'No results found! 😢',

                        borderStyle: 'border-bottom',
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
                        (newValue) => {
                            if (newValue) getStates();
                            selectedCountryValid = newValue;
                        }
                    "
                />
                <SearchInput
                    v-model="selectedState"
                    :disabled="states.length === 0"
                    :label="$t('labels.country.county')"
                    type="text"
                    name="Company state"
                    :options="{
                        leftIcon: mdiEarth,
                        borderStyle: 'bordered',
                        heroiconsXMark: heroiconsXMark,
                        noResultMessage: 'No results found! 😢',

                        borderStyle: 'border-bottom',
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
                        (newValue) => {
                            if (newValue) getTowns();
                            selectedStateValid = newValue;
                        }
                    "
                />
            </div>
            <div class="flex w-full flex-col gap-4 md:flex-row">
                <SearchInput
                    v-model="selectedTown"
                    :disabled="towns.length === 0"
                    :label="$t('labels.country.town')"
                    type="text"
                    name="Company town"
                    :options="{
                        leftIcon: mdiEarth,
                        borderStyle: 'bordered',
                        heroiconsXMark: heroiconsXMark,
                        noResultMessage: 'No results found! 😢',

                        borderStyle: 'border-bottom',
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
                    :disabled="
                        selectedTown.length === 0 || selectedTownValid === false
                    "
                    :options="{
                        leftIcon: mdiMapMarker,

                        borderStyle: 'border-bottom',
                    }"
                    v-model="form.address"
                    :label="$t('labels.address')"
                    :error="form.errors.address"
                />
            </div>
            <FilePond
                v-if="isClient && FilePond"
                id="logo-upload"
                @processfile="onProcessFile"
                :server="
                    filePondServer(csrfToken, form.logo, form.logo_extension)
                "
                ref="filePondRef"
                class="w-full"
                :label-idle="$t('labels.logo.drop')"
                accepted-file-types="image/jpeg, image/png"
            />
            <p
                v-if="form.errors.logo"
                class="w-full px-1 pt-1 text-sm font-medium text-red-500"
            >
                {{ form.errors.logo }}
            </p>
            <p class="text-center text-lg">
                {{ $t('labels.companyDetails.description') }}
            </p>
            <div>
                <QuillEditor
                    v-if="isClient && QuillEditor"
                    name="Company description"
                    ref="quillRef"
                    toolbar="essential"
                    theme="snow"
                />
            </div>
            <p
                v-if="form.errors.description"
                class="w-full px-1 pt-1 text-sm font-medium text-red-500"
            >
                {{ form.errors.description }}
            </p>
            <div class="flex w-full justify-center gap-4">
                <Button
                    @click="
                        () => {
                            submit();
                        }
                    "
                    class="mt-4 w-24"
                    :options="{ color: 'blue' }"
                    >{{ $t('common.create') }}</Button
                >
            </div>
        </div>
    </div>
</template>

<style>
.filepond--credits {
    @apply dark:text-zinc-100;
}
.filepond--drop-label {
    @apply dark:text-zinc-300;
}
.filepond--panel,
.filepond--panel-root {
    @apply dark:bg-zinc-700;
}
.filepond--wrapper {
    @apply bg-zinc-700/0;
}

.ql-toolbar .ql-stroke {
    @apply dark:fill-none dark:stroke-zinc-100;
}

.ql-picker-options {
    @apply dark:!bg-zinc-800 dark:text-zinc-100 dark:selection:bg-black dark:hover:bg-zinc-600;
}

.ql-picker-item .ql-selected {
    @apply dark:bg-zinc-600;
}
.ql-snow.ql-toolbar button.ql-active,
.ql-snow .ql-toolbar button.ql-active,
.ql-snow.ql-toolbar .ql-picker-label.ql-active,
.ql-snow .ql-toolbar .ql-picker-label.ql-active,
.ql-snow.ql-toolbar .ql-picker-item.ql-selected,
.ql-snow .ql-toolbar .ql-picker-item.ql-selected {
    @apply dark:bg-zinc-600;
}

.ql-snow.ql-toolbar button:hover,
.ql-snow .ql-toolbar button:hover,
.ql-snow.ql-toolbar button:focus,
.ql-snow .ql-toolbar button:focus,
.ql-snow.ql-toolbar .ql-picker-label:hover,
.ql-snow .ql-toolbar .ql-picker-label:hover,
.ql-snow.ql-toolbar .ql-picker-item:hover,
.ql-snow .ql-toolbar .ql-picker-item:hover {
    @apply dark:bg-zinc-600;
}

.ql-snow.ql-toolbar button.ql-active,
.ql-snow .ql-toolbar button.ql-active,
.ql-snow.ql-toolbar .ql-picker-label.ql-active,
.ql-snow .ql-toolbar .ql-picker-label.ql-active,
.ql-snow.ql-toolbar .ql-picker-item.ql-selected,
.ql-snow .ql-toolbar .ql-picker-item.ql-selected {
    @apply dark:text-zinc-100;
}
</style>
