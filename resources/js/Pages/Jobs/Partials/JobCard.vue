<script setup>
import RatingStars from '@/Components/RatingStars.vue';
import { useCurrentUser } from '@/Composables/useCurrentUser';
import Job from '@/Models/Job';
import { useModalStore } from '@/Stores/modalStore';
import { Link, router } from '@inertiajs/vue3';

const modalStore = useModalStore();
const currentUser = useCurrentUser();

const props = defineProps({
    job: {
        type: Job,
        required: true,
    },
    alreadyApplied: {
        type: Boolean,
        default: false,
    },
    class: {
        type: String,
        default: '',
    },
    edit: {
        type: Boolean,
        default: false,
    },
});
</script>
<template>
    <div
        :id="`job-card-${job.title}`"
        :class="class"
        class="group container relative flex w-full flex-col justify-between overflow-hidden rounded bg-white p-6 shadow-md dark:bg-zinc-800 dark:text-zinc-100 md:w-[15em]"
    >
        <!-- Logo and Company Name -->

        <div class="relative flex flex-col items-center gap-2">
            <Link
                class="flex w-full flex-col gap-1"
                :href="route('companies.show', { company: job.company.slug })"
            >
                <img
                    @error="
                        (e) => {
                            e.target.src = '/images/logo/broken-image.png';
                        }
                    "
                    class="h-12 fill-current object-contain text-zinc-500"
                    :src="job.company.logoPath()"
                    alt="Logo"
                />
                <h2 class="mb-2 text-center text-xs font-bold uppercase">
                    <!-- Job Title -->
                    {{ job.company.name }}
                </h2>
            </Link>
            <h2 class="mb-2 text-center font-bold uppercase">
                {{ job.title }}
            </h2>
            <RatingStars :rating.number="3.5"></RatingStars>
            <div class="flex flex-col items-center gap-2">
                <div class="flex items-center gap-2 text-sm">
                    <span class="font-bold uppercase">{{
                        $t('labels.salary')
                    }}</span>
                    {{
                        job.salary
                            ? `$${job.salary}`
                            : $t('labels.confidential')
                    }}
                </div>
                <div class="flex items-center gap-2 text-sm">
                    <span class="font-bold uppercase">{{
                        $t('labels.country.town')
                    }}</span>
                    {{ job.location }}
                </div>
                <div class="flex items-center gap-2 text-sm">
                    <span class="font-bold uppercase">{{
                        $t('labels.type')
                    }}</span>
                    {{ job.type }}
                </div>
            </div>
            <div
                v-if="props.alreadyApplied === false"
                class="mt-3 flex w-[10em] flex-col items-center gap-3 md:w-full"
            >
                <Button
                    @click="
                        () => {
                            if (currentUser.isSet()) {
                                modalStore.openModal('jobApply');
                            } else {
                                router.visit(route('connect'));
                            }
                        }
                    "
                    class="w-full"
                    :options="{
                        shape: 'pill',
                        color: 'blue',
                    }"
                    >{{
                        currentUser.isSet()
                            ? $t('common.apply.self')
                            : $t('common.apply.login')
                    }}</Button
                >
            </div>
        </div>
    </div>
</template>
