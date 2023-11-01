<script setup>
import { AtSymbolIcon, MapPinIcon, PhoneIcon } from '@heroicons/vue/24/outline';
import CompanyCard from './Partials/CompanyCard.vue';
import { ref, onMounted, onBeforeMount } from 'vue';
import OwnerCard from './Partials/OwnerCard.vue';
import JobCard from './Partials/JobCard.vue';
import { markRaw } from 'vue';
import { useModalStore } from '@/Stores/modalStore';

const modalStore = useModalStore();
const isClient = ref(false);
const GoogleMap = ref(null);
const Marker = ref(null);

onBeforeMount(() => {
    isClient.value = typeof window !== 'undefined';
});

onMounted(async () => {
    if (isClient.value) {
        const { GoogleMap: GMap, Marker: GMarker } = await import(
            'vue3-google-map'
        );
        GoogleMap.value = markRaw(GMap);
        Marker.value = markRaw(GMarker);
    }
});

const apiKey = import.meta.env.VITE_GOOGLE_MAPS_API_KEY;
const center = ref({ lat: 0, lng: 0 });
const props = defineProps({
    company: {
        type: Object,
        required: true,
    },
    isOwner: {
        type: Boolean,
        default: false,
    },
});

const edit = ref(false);

const mapStyles = ref([
    {
        featureType: 'all',
        elementType: 'geometry.fill',
        stylers: [
            {
                weight: '2.00',
            },
        ],
    },
    {
        featureType: 'all',
        elementType: 'geometry.stroke',
        stylers: [
            {
                color: '#9c9c9c',
            },
        ],
    },
    {
        featureType: 'all',
        elementType: 'labels.text',
        stylers: [
            {
                visibility: 'on',
            },
        ],
    },
    {
        featureType: 'landscape',
        elementType: 'all',
        stylers: [
            {
                color: '#f2f2f2',
            },
        ],
    },
    {
        featureType: 'landscape',
        elementType: 'geometry.fill',
        stylers: [
            {
                color: '#ffffff',
            },
        ],
    },
    {
        featureType: 'landscape.man_made',
        elementType: 'geometry.fill',
        stylers: [
            {
                color: '#ffffff',
            },
        ],
    },
    {
        featureType: 'poi',
        elementType: 'all',
        stylers: [
            {
                visibility: 'off',
            },
        ],
    },
    {
        featureType: 'road',
        elementType: 'all',
        stylers: [
            {
                saturation: -100,
            },
            {
                lightness: 45,
            },
        ],
    },
    {
        featureType: 'road',
        elementType: 'geometry.fill',
        stylers: [
            {
                color: '#eeeeee',
            },
        ],
    },
    {
        featureType: 'road',
        elementType: 'labels.text.fill',
        stylers: [
            {
                color: '#7b7b7b',
            },
        ],
    },
    {
        featureType: 'road',
        elementType: 'labels.text.stroke',
        stylers: [
            {
                color: '#ffffff',
            },
        ],
    },
    {
        featureType: 'road.highway',
        elementType: 'all',
        stylers: [
            {
                visibility: 'simplified',
            },
        ],
    },
    {
        featureType: 'road.arterial',
        elementType: 'labels.icon',
        stylers: [
            {
                visibility: 'off',
            },
        ],
    },
    {
        featureType: 'transit',
        elementType: 'all',
        stylers: [
            {
                visibility: 'off',
            },
        ],
    },
    {
        featureType: 'water',
        elementType: 'all',
        stylers: [
            {
                color: '#46bcec',
            },
            {
                visibility: 'on',
            },
        ],
    },
    {
        featureType: 'water',
        elementType: 'geometry.fill',
        stylers: [
            {
                color: '#c8d7d4',
            },
        ],
    },
    {
        featureType: 'water',
        elementType: 'labels.text.fill',
        stylers: [
            {
                color: '#070707',
            },
        ],
    },
    {
        featureType: 'water',
        elementType: 'labels.text.stroke',
        stylers: [
            {
                color: '#ffffff',
            },
        ],
    },
]);
const geocodeAddress = async (address) => {
    try {
        const response = await fetch(
            `https://maps.googleapis.com/maps/api/geocode/json?address=${encodeURIComponent(
                address,
            )}&key=${apiKey}`,
        );
        const data = await response.json();
        if (data.status === 'OK') {
            const { lat, lng } = data.results[0].geometry.location;
            center.value.lat = lat;
            center.value.lng = lng;
        } else {
            console.error('Geocoding error:', data.status);
        }
    } catch (error) {
        console.error('Error fetching geocoding results:', error);
    }
};

// When the component mounts, geocode the address
onMounted(() => {
    geocodeAddress(
        `${props.company.address}, ${props.company.town}, ${props.company.country}, ${props.company.state}`,
    );
});
</script>

<template>
    <div
        class="mt-12 flex w-full max-w-screen-lg flex-wrap justify-center gap-8 p-6 md:flex-nowrap"
    >
        <!-- Div-ul cu detaliile companiei -->
        <div class="flex w-full flex-col gap-4 md:w-auto">
            <CompanyCard :edit="isOwner && edit" :company="company" />
            <OwnerCard
                v-if="isOwner"
                :applications="company.application_number"
                :toggle-edit="
                    (value) => {
                        edit = value;
                    }
                "
            />
        </div>

        <!-- Div-ul cu descrierea companiei -->
        <div
            class="flex w-full flex-grow flex-col justify-start gap-4 md:w-3/4"
        >
            <div
                :id="edit ? 'company-description-edit' : ''"
                class="group container relative flex w-full flex-col gap-4 rounded bg-white p-6 shadow-md"
                :class="{
                    'cursor-pointer': edit,
                }"
                @click="
                    () => {
                        if (edit) {
                            modalStore.openModal('companyDescription');
                        }
                    }
                "
            >
                <h2
                    v-if="edit"
                    class="absolute right-0 top-0 pr-2 font-extrabold text-gray-500 transition-all duration-150 ease-in-out group-hover:text-black"
                >
                    Click field to edit
                </h2>
                <h2 class="text-lg font-bold uppercase text-black/60">
                    Description
                </h2>
                <div class="ql-editor prose" v-html="company.description"></div>
            </div>
            <div
                class="container flex w-full flex-col gap-4 rounded bg-white p-6 shadow-md"
            >
                <h2 class="text-lg font-bold uppercase text-black/60">Jobs</h2>
                <p class="text-sm font-bold">
                    There are no jobs available at the moment
                </p>
                <JobCard v-for="job in company.jobs" :key="job.id" :job="job" />
            </div>
            <div
                :id="edit ? 'company-contact-edit' : ''"
                @click="
                    () => {
                        if (edit) {
                            modalStore.openModal('companyContact');
                        }
                    }
                "
                :class="{
                    'cursor-pointer': edit,
                }"
                class="group container relative flex w-full flex-col gap-4 rounded bg-white p-6 shadow-md"
            >
                <h2
                    v-if="edit"
                    class="absolute right-0 top-0 pr-2 font-extrabold text-gray-500 transition-all duration-150 ease-in-out group-hover:text-black"
                >
                    Click field to edit
                </h2>
                <h2 class="text-lg font-bold uppercase text-black/60">
                    Contact
                </h2>
                <div class="flex flex-col items-start gap-2">
                    <div class="flex flex-col gap-2">
                        <h2
                            class="flex flex-row items-center gap-1 text-sm font-bold"
                        >
                            <span class="h-4 w-4"><MapPinIcon /></span
                            >{{ company.town }}, {{ company.country }} ({{
                                company.state
                            }})
                        </h2>
                        <h2
                            class="flex flex-row items-center gap-1 text-sm font-bold"
                        >
                            <span class="h-4 w-4"><MapPinIcon /></span
                            >{{ company.address }}
                        </h2>
                        <h2
                            class="flex flex-row items-center gap-1 text-sm font-bold"
                        >
                            <span class="h-4 w-4"><PhoneIcon /></span
                            >{{ company.phone_number }}
                        </h2>
                        <h2
                            class="flex flex-row items-center gap-1 text-sm font-bold"
                        >
                            <span class="h-4 w-4"><AtSymbolIcon /></span
                            >{{ company.email }}
                        </h2>
                    </div>
                    <GoogleMap
                        v-if="
                            center.lat !== 0 &&
                            center.lng !== 0 &&
                            isClient &&
                            GoogleMap &&
                            Marker
                        "
                        :api-key="apiKey"
                        class="h-96 w-full rounded"
                        :styles="mapStyles"
                        :streetViewControl="false"
                        :mapTypeControl="false"
                        :center="center"
                        :zoom="15"
                    >
                        <Marker
                            :options="{
                                position: center,
                                title: company.name,
                                label: company.name[0],
                            }"
                        />
                    </GoogleMap>
                </div>
            </div>
        </div>
    </div>
</template>
