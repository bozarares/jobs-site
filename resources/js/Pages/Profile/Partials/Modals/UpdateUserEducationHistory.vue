<script setup>
// TODO: Change form with axios
// TODO: Change controller so it will send a response

import { useForm, usePage } from '@inertiajs/vue3';

import mdiCalendarArrowLeft from '~icons/mdi/calendar-arrow-left';
import mdiCalendarArrowRight from '~icons/mdi/calendar-arrow-right';
import mdiFileDocument from '~icons/mdi/file-document';
import heroiconsAcademicCap from '~icons/heroicons/academic-cap';

import dayjs from 'dayjs';
import { useProfileStore } from '@/Stores/profileStore';

const props = defineProps({
    closeModal: { type: Function, default: () => {} },
});

const profileStore = useProfileStore();
const page = usePage();
const educationHistory = ref(profileStore.educationHistory);

const toDate = ref(false);
const form = useForm({
    id: null,
    institution: '',
    degree: '',
    field_of_study: '',
    start_date: null,
    end_date: null,
    locale: profileStore.profileLanguage,
});

const editMode = ref(false);

const resetForm = () => {
    form.reset();
    form.clearErrors();
    editMode.value = false;
    toDate.value = false;
};

watch(
    () => profileStore.educationHistory,
    (newValue) => {
        educationHistory.value = newValue;
    },
);

const submitAdd = () => {
    form.post(route('profile.update.educationHistory.add'), {
        onSuccess: () => {
            profileStore.setProfileWatcher(true);
            resetForm();
        },
    });
};

const submitEdit = () => {
    form.put(route('profile.update.educationHistory.edit'), {
        onSuccess: () => {
            profileStore.setProfileWatcher(true);
            resetForm();
        },
    });
};

const submitDelete = () => {
    form.delete(route('profile.update.educationHistory.delete'), {
        onSuccess: () => {
            profileStore.setProfileWatcher(true);
            resetForm();
        },
    });
};

function formatDateToMySQL(dateObj) {
    const yyyy = dateObj.getFullYear();
    const mm = String(dateObj.getMonth() + 1).padStart(2, '0');
    const dd = String(dateObj.getDate()).padStart(2, '0');
    const hh = String(dateObj.getHours()).padStart(2, '0');
    const mi = String(dateObj.getMinutes()).padStart(2, '0');
    const ss = String(dateObj.getSeconds()).padStart(2, '0');

    return `${yyyy}-${mm}-${dd} ${hh}:${mi}:${ss}`;
}
const startDateComputed = computed({
    get() {
        return form.start_date ? dayjs(form.start_date).toDate() : null;
    },
    set(value) {
        form.start_date = value
            ? formatDateToMySQL(dayjs(value).toDate())
            : null;
    },
});

const endDateComputed = computed({
    get() {
        return form.end_date ? dayjs(form.end_date).toDate() : null;
    },
    set(value) {
        if (value === null) toDate.value = true;

        form.end_date = value ? formatDateToMySQL(dayjs(value).toDate()) : null;
    },
});
</script>

<template>
    <div
        id="education-history-modal"
        class="container relative mx-auto flex max-h-[35em] max-w-lg flex-col gap-8 overflow-auto rounded bg-white p-8 text-zinc-800 shadow dark:bg-zinc-800 dark:text-zinc-100"
    >
        <div
            v-if="educationHistory.length !== 0"
            v-for="education in educationHistory"
            :key="education.id"
            class="flex items-center justify-between border-b-2 pb-4 pt-4 first:pt-0 last:border-b-0 last:pb-0"
        >
            <div>
                <div class="text-lg font-bold">
                    {{ education.institution }}
                    <span class="text-base text-zinc-600 dark:text-zinc-200">{{
                        education.degree
                    }}</span>
                </div>
                <div class="text-zinc-600 dark:text-zinc-200">
                    {{ education.field_of_study }}
                </div>
                <div class="text-sm text-zinc-600 dark:text-zinc-200">
                    {{ education.start_date }} -
                    {{ education.end_date ? education.end_date : 'To date' }}
                </div>
            </div>
            <div class="flex gap-2">
                <Button
                    @click="
                        () => {
                            editMode = true;
                            form.id = education.id;
                            form.institution = education.institution;
                            form.degree = education.degree;
                            form.field_of_study = education.field_of_study;
                            startDateComputed = education.start_date;
                            endDateComputed = education.end_date;
                            toDate = education.end_date === null ? true : false;
                        }
                    "
                    class="text-sm"
                    :options="{ color: 'blue', shape: 'pill' }"
                    >{{ $t('common.edit') }}</Button
                >
                <Button
                    @click="
                        () => {
                            form.id = education.id;
                            submitDelete();
                        }
                    "
                    class="text-sm"
                    :options="{ color: 'red', shape: 'pill' }"
                    >{{ $t('common.delete') }}</Button
                >
            </div>
        </div>
        <div v-else class="font-bold text-zinc-500 dark:text-zinc-300">
            {{ $t('fallbacks.educationHistory') }}
        </div>
    </div>
    <div
        class="container relative mx-auto flex max-h-[35em] max-w-lg flex-col gap-8 overflow-visible rounded bg-white p-8 text-zinc-500 shadow dark:bg-zinc-800 dark:text-zinc-300"
    >
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-bold uppercase">
                {{ $t('labels.educationHistory.edit') }}
            </h2>
            <Heroicons:xMark class="h-6 cursor-pointer" @click="closeModal()" />
        </div>

        <div class="flex flex-col gap-4 overflow-visible">
            <div class="flex gap-4">
                <Input
                    v-model="form.institution"
                    :error="form.errors.institution"
                    :label="$t('labels.educationHistory.institution')"
                    type="text"
                    name="institution"
                    :options="{
                        leftIcon: heroiconsAcademicCap,
                        size: 'small',
                        borderStyle: 'border-bottom',
                    }"
                />
                <Input
                    v-model="form.degree"
                    :error="form.errors.degree"
                    :label="$t('labels.educationHistory.degree')"
                    type="text"
                    name="degree"
                    :options="{
                        leftIcon: mdiFileDocument,
                        size: 'small',
                        borderStyle: 'border-bottom',
                    }"
                />
            </div>
            <Input
                v-model="form.field_of_study"
                :error="form.errors.field_of_study"
                :label="$t('labels.educationHistory.field')"
                type="text"
                name="field_of_study"
                :options="{
                    leftIcon: heroiconsAcademicCap,
                    size: 'small',
                    borderStyle: 'border-bottom',
                }"
            />
            <DateTime
                v-model="startDateComputed"
                class=""
                :label="$t('labels.startDate')"
                name="start_date"
                :dateOptions="{
                    minDate: new Date(1900, 0, 0),
                    maxDate: new Date(2025, 0, 0),
                }"
                :options="{
                    leftIcon: mdiCalendarArrowLeft,
                    borderStyle: 'border-bottom',
                }"
            ></DateTime>
            <div class="flex flex-col items-center justify-center">
                <DateTime
                    v-model="endDateComputed"
                    :disabled="toDate"
                    class=""
                    :label="$t('labels.endDate')"
                    name="end_date"
                    :dateOptions="{
                        minDate: new Date(1900, 0, 0),
                        maxDate: new Date(2025, 0, 0),
                    }"
                    :options="{
                        leftIcon: mdiCalendarArrowRight,
                        borderStyle: 'border-bottom',
                    }"
                />
                <Checkbox v-model="toDate" :label="$t('labels.toDate')" />
            </div>
        </div>
        <div v-if="!editMode" class="flex w-full items-center">
            <Button
                @click="
                    () => {
                        editMode ? submitEdit() : submitAdd();
                    }
                "
                class="w-full"
                :options="{ color: 'blue', shape: 'pill' }"
                >{{ $t('common.add') }}</Button
            >
        </div>
        <div v-else class="flex w-full items-center gap-4">
            <Button
                @click="
                    () => {
                        editMode = false;
                        form.institution = '';
                        form.degree = '';
                        form.field_of_study = '';
                        startDateComputed = null;
                        endDateComputed = null;
                        toDate = false;
                    }
                "
                class="w-full"
                :options="{ color: 'red', shape: 'pill' }"
                >{{ $t('common.cancel') }}</Button
            >
            <Button
                @click="
                    () => {
                        editMode ? submitEdit() : submitAdd();
                    }
                "
                class="w-full"
                :options="{ color: 'blue', shape: 'pill' }"
                >{{ $t('common.edit') }}</Button
            >
        </div>
    </div>
</template>
