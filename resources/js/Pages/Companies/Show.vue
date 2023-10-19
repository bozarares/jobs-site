<script setup>
import { AtSymbolIcon, MapPinIcon, PhoneIcon } from '@heroicons/vue/24/outline';
import CompanyCard from './Partials/CompanyCard.vue';
import RecruiterCard from './Partials/RecruiterCard.vue';
import { ref } from 'vue';
import { computed } from 'vue';
import { GoogleMap, Marker } from 'vue3-google-map';
import { onMounted } from 'vue';

const location = ref(null);
const apiKey = import.meta.env.VITE_GOOGLE_MAPS_API_KEY;
const center = ref({ lat: 0, lng: 0 });
const props = defineProps({
    company: {
        type: Object,
        required: true,
    },
});
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
        class="flex flex-wrap md:flex-nowrap justify-center gap-8 w-full mt-12 p-6 max-w-screen-lg"
    >
        <!-- Div-ul cu detaliile companiei -->
        <div class="flex flex-col gap-4 w-full md:w-auto">
            <CompanyCard :company="company" />
            <RecruiterCard></RecruiterCard>
        </div>

        <!-- Div-ul cu descrierea companiei -->
        <div
            class="flex-grow w-full md:w-3/4 flex flex-col justify-start gap-4"
        >
            <div
                class="container p-6 bg-white rounded shadow-md flex flex-col gap-4 w-full"
            >
                <h2 class="text-lg font-bold uppercase text-black/60">
                    Description
                </h2>
                <div v-html="company.description"></div>
            </div>
            <div
                class="container p-6 bg-white rounded shadow-md flex flex-col gap-4 w-full"
            >
                <h2 class="text-lg font-bold uppercase text-black/60">Jobs</h2>
                <p class="font-bold text-sm">
                    There are no jobs available at the moment
                </p>
            </div>
            <div
                class="container p-6 bg-white rounded shadow-md flex flex-col gap-4 w-full"
            >
                <h2 class="text-lg font-bold uppercase text-black/60">
                    Contact
                </h2>
                <div class="flex flex-col items-start gap-2">
                    <div class="flex flex-col gap-2">
                        <h2
                            class="text-sm flex flex-row gap-1 items-center font-bold"
                        >
                            <span class="w-4 h-4"><MapPinIcon /></span
                            >{{ company.town }}, {{ company.country }} ({{
                                company.state
                            }})
                        </h2>
                        <h2
                            class="text-sm flex flex-row gap-1 items-center font-bold"
                        >
                            <span class="w-4 h-4"><MapPinIcon /></span
                            >{{ company.address }}
                        </h2>
                        <h2
                            class="text-sm flex flex-row gap-1 items-center font-bold"
                        >
                            <span class="w-4 h-4"><PhoneIcon /></span
                            >{{ company.phone_number }}
                        </h2>
                        <h2
                            class="text-sm flex flex-row gap-1 items-center font-bold"
                        >
                            <span class="w-4 h-4"><AtSymbolIcon /></span
                            >{{ company.email }}
                        </h2>
                    </div>
                    <GoogleMap
                        v-if="center.lat !== 0 && center.lng !== 0"
                        :api-key="apiKey"
                        class="w-full h-96 rounded"
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
