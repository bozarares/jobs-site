<script setup>
import { AtSymbolIcon, MapPinIcon, PhoneIcon } from '@heroicons/vue/24/outline';
import CompanyCard from './Partials/CompanyCard.vue';
import OwnerCard from './Partials/OwnerCard.vue';
import JobCard from './Partials/JobCard.vue';
import { useModalStore } from '@/Stores/modalStore';
import ContentCard from '@/Components/ContentCard.vue';
import AddJob from './Partials/AddJob.vue';
import { Head } from '@inertiajs/vue3';
import Company from '@/Models/Company';
import Job from '@/Models/Job';

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

const companyInstance = computed(() => {
    return new Company(props.company);
});

const jobInstances = computed(() => {
    return companyInstance.value.jobs.map((jobData) => new Job(jobData));
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

onMounted(() => {
    geocodeAddress(
        `${props.company.location.address}, ${props.company.location.town}, ${props.company.location.country}, ${props.company.location.state}`,
    );
});
</script>

<template>
    <Head :title="props.company.name" />

    <div
        class="mt-12 flex w-full max-w-screen-lg flex-wrap justify-center gap-8 p-6 md:flex-nowrap"
    >
        <!-- Div-ul cu detaliile companiei -->
        <div class="flex w-full flex-col items-center gap-4 md:w-auto">
            <CompanyCard :edit="isOwner && edit" :company="companyInstance" />
            <OwnerCard
                v-if="isOwner"
                :applications="companyInstance.application_number"
                :toggle-edit="
                    (value) => {
                        edit = value;
                    }
                "
            />
        </div>

        <div
            class="flex w-full flex-grow flex-col items-center justify-start gap-4 md:w-3/4"
        >
            <!-- Description -->
            <ContentCard
                :title="$t('labels.description.self')"
                :edit="edit"
                id-edit="company-description-edit"
                modal="companyDescription"
            >
                <div
                    class="ql-editor prose dark:!text-zinc-100"
                    v-html="companyInstance.description"
                />
            </ContentCard>

            <!-- Jobs -->
            <ContentCard :title="$tc('labels.job.self', 2)">
                <p class="text-sm font-bold" v-if="jobInstances.length === 0">
                    There are no jobs available at the moment
                </p>
                <div class="flex flex-wrap items-center justify-center gap-2">
                    <JobCard
                        v-for="job in jobInstances"
                        :key="job.slug"
                        :job="job"
                    />
                    <AddJob
                        v-if="isOwner && edit"
                        @click="modalStore.openModal('companyCreateJob')"
                    />
                </div>
            </ContentCard>

            <!-- Contact -->
            <ContentCard
                :title="$t('labels.contact.self')"
                :edit="edit"
                id-edit="company-contact-edit"
                modal="companyContact"
            >
                <div class="flex flex-col items-start gap-2">
                    <div class="flex flex-col gap-2">
                        <h2
                            class="flex flex-row items-center gap-1 text-sm font-bold"
                        >
                            <span class="h-4 w-4"><MapPinIcon /></span
                            >{{ companyInstance.location.town }},
                            {{ companyInstance.location.country }} ({{
                                companyInstance.location.state
                            }})
                        </h2>
                        <h2
                            class="flex flex-row items-center gap-1 text-sm font-bold"
                        >
                            <span class="h-4 w-4"><MapPinIcon /></span
                            >{{ companyInstance.location.address }}
                        </h2>
                        <h2
                            class="flex flex-row items-center gap-1 text-sm font-bold"
                        >
                            <span class="h-4 w-4"><PhoneIcon /></span
                            >{{ companyInstance.contact.phone_number }}
                        </h2>
                        <h2
                            class="flex flex-row items-center gap-1 text-sm font-bold"
                        >
                            <span class="h-4 w-4"><AtSymbolIcon /></span
                            >{{ companyInstance.contact.email }}
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
                                title: companyInstance.name,
                                label: companyInstance.name[0],
                            }"
                        />
                    </GoogleMap>
                </div>
            </ContentCard>
        </div>
    </div>
</template>
