<script setup>
import Company from '@/Models/Company';
import { useModalStore } from '@/Stores/modalStore';
import mdiHeart from '~icons/mdi/heart';
import mdiShare from '~icons/mdi/share';
import mdiEye from '~icons/mdi/eye';

import { Link } from '@inertiajs/vue3';

const modalStore = useModalStore();

const props = defineProps({
    company: {
        type: Company,
        required: true,
    },
    class: {
        type: String,
        default: '',
    },
    edit: {
        type: Boolean,
        default: false,
    },
    viewButton: {
        type: Boolean,
        default: false,
    },
});
</script>
<template>
    <div
        :id="`company-card-${company.name}`"
        :class="class"
        class="group container relative flex w-full flex-col justify-between overflow-hidden rounded bg-white p-6 shadow-md dark:bg-zinc-800 dark:text-zinc-100 md:w-[15em]"
    >
        <!-- Logo and Company Name -->

        <div class="relative flex flex-col items-center gap-2">
            <img
                :id="edit ? 'company-logo-edit' : ''"
                @click="
                    () => {
                        if (edit) {
                            modalStore.openModal('companyLogo');
                        }
                    }
                "
                @error="
                    (e) => {
                        e.target.src = '/images/logo/broken-image.png';
                    }
                "
                :class="{
                    'cursor-pointer': edit,
                }"
                class="h-12 fill-current object-contain text-zinc-500"
                :src="company.logoPath()"
                alt="Logo"
            />
            <h2
                v-if="edit"
                class="pr-2 text-sm font-extrabold text-zinc-500 transition-all duration-150 ease-in-out group-hover:text-black"
            >
                {{ $t('labels.clickToEdit.logo') }}
            </h2>
            <!-- Job Title -->
            <h2 class="mb-2 text-center text-lg font-bold uppercase">
                {{ company.name }}
            </h2>
            <div class="flex flex-col items-center">
                <h2
                    class="flex flex-row items-center gap-1 text-center text-sm font-semibold"
                >
                    <span class="h-4 w-4"><Mdi:mapMarker /></span
                    >{{ company.location.state }},
                    {{ company.location.country }}
                </h2>
                <h2
                    class="mb-2 flex flex-row items-center gap-1 text-center text-sm font-semibold"
                >
                    {{ company.town }}
                </h2>
            </div>
            <RatingStars :rating.number="3.5"></RatingStars>
            <div
                class="mt-3 flex w-[10em] flex-col items-center gap-3 md:w-full"
            >
                <Button
                    class="w-full"
                    :options="{
                        shape: 'pill',
                        color: 'blue',
                        leftIcon: mdiShare,
                    }"
                    >{{ $t('common.share') }}</Button
                >
                <Button
                    :id="`company-view-${company.name}`"
                    v-if="viewButton"
                    class="w-full"
                    as="a"
                    :is="Link"
                    :href="route('companies.show', company.slug)"
                    :options="{
                        shape: 'pill',
                        color: 'blue',
                        leftIcon: mdiEye,
                    }"
                    >{{ $t('common.view') }}</Button
                >
                <Button
                    class="w-full"
                    :options="{
                        shape: 'pill',
                        color: 'blue',
                        leftIcon: mdiHeart,
                    }"
                    >{{ $t('common.follow') }}</Button
                >
            </div>
        </div>
    </div>
</template>
