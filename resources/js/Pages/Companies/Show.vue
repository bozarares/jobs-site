<script setup>
import {
    AtSymbolIcon,
    MapPinIcon,
    PencilSquareIcon,
    PhoneIcon,
} from '@heroicons/vue/24/outline';
import CompanyCard from './Partials/CompanyCard.vue';
import { ref } from 'vue';
import { GoogleMap, Marker } from 'vue3-google-map';
import { onMounted } from 'vue';
import { Button } from '@/Components/UI';
import EditModal from './Partials/EditModal.vue';
import OwnerCard from './Partials/OwnerCard.vue';

const location = ref(null);
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

const showModal = ref(false);
const modalType = ref('');
const openModal = (type) => {
    modalType.value = type;
    showModal.value = true;
};
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
                :id="`description-${company.name}`"
                class="group container relative flex w-full flex-col gap-4 rounded bg-white p-6 shadow-md"
            >
                <Button
                    :id="`edit-description-button-${company.name}`"
                    v-if="isOwner && edit"
                    @click="openModal('description')"
                    class="absolute right-0 top-0 scale-75"
                    :options="{ leftIcon: PencilSquareIcon }"
                />
                <h2 class="text-lg font-bold uppercase text-black/60">
                    Description
                </h2>
                <div v-html="company.description"></div>
            </div>
            <div
                class="container flex w-full flex-col gap-4 rounded bg-white p-6 shadow-md"
            >
                <h2 class="text-lg font-bold uppercase text-black/60">Jobs</h2>
                <p class="text-sm font-bold">
                    There are no jobs available at the moment
                </p>
            </div>
            <div
                class="group container relative flex w-full flex-col gap-4 rounded bg-white p-6 shadow-md"
            >
                <Button
                    :id="`edit-contact-button-${company.name}`"
                    v-if="isOwner && edit"
                    @click="openModal('details')"
                    class="absolute right-0 top-0 scale-75"
                    :options="{ leftIcon: PencilSquareIcon }"
                />
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
                        v-if="center.lat !== 0 && center.lng !== 0"
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
    <EditModal
        v-if="showModal"
        :company="company"
        :show="showModal"
        :type="modalType"
        @update:show="showModal = $event"
    />
</template>
