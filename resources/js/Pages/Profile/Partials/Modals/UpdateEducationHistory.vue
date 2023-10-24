<script setup>
import { useForm, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
import { Button, Checkbox, DateTime, Timeline } from '@/Components/UI';
import Input from '@/Components/UI/Input/Input.vue';
import {
    CalendarDaysIcon,
    PhoneIcon,
    UserIcon,
} from '@heroicons/vue/24/outline';
import { computed } from 'vue';
import dayjs from 'dayjs';
import { onMounted } from 'vue';

const props = defineProps({
    closeModal: { type: Function, default: () => {} },
});

const page = usePage();
const educationHistory = ref(page.props.user.education_history);

const toDate = ref(false);
const form = useForm({
    id: null,
    institution: '',
    degree: '',
    field_of_study: '',
    start_date: null,
    end_date: null,
});

const editMode = ref(false);

const resetForm = () => {
    form.reset();
    form.clearErrors();
    editMode.value = false;
    toDate.value = false;
};

const submitAdd = () => {
    form.post(route('profile.update.educationHistory'), {
        onSuccess: (response) => {
            educationHistory.value = page.props.user.education_history;
            resetForm();
        },
        onFinish: () => {
            // props.closeModal();
        },
    });
};

const submitEdit = () => {
    form.put(route('profile.update.educationHistory'), {
        onSuccess: (response) => {
            educationHistory.value = page.props.user.education_history;
            resetForm();
        },
        onFinish: () => {
            // props.closeModal();
        },
    });
};

const submitDelete = () => {
    form.delete(route('profile.update.educationHistory'), {
        onSuccess: (response) => {
            educationHistory.value = page.props.user.education_history;
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
    <Teleport to="body">
        <div
            class="fixed inset-0 z-50 flex flex-col items-center justify-center gap-4 overflow-auto md:flex-row"
        >
            <!-- Backdrop -->
            <div
                @click="closeModal()"
                class="absolute inset-0 h-full bg-black opacity-50"
            ></div>
            <div
                class="container relative flex max-h-[calc(100vh-2rem)] max-w-lg flex-col overflow-y-auto rounded bg-white p-8 shadow"
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
                            <span class="text-base text-gray-600">{{
                                education.degree
                            }}</span>
                        </div>
                        <div>{{ education.field_of_study }}</div>
                        <div class="text-sm text-gray-600">
                            {{ education.start_date }} -
                            {{
                                education.end_date
                                    ? education.end_date
                                    : 'To date'
                            }}
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
                                    form.field_of_study =
                                        education.field_of_study;
                                    startDateComputed = education.start_date;
                                    endDateComputed = education.end_date;

                                    // startDateComputed = new Date(1998, 0, 1);
                                    // endDateComputed = new Date(1998, 0, 1);
                                }
                            "
                            class="text-sm"
                            :options="{ color: 'blue', shape: 'pill' }"
                            >Edit</Button
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
                            >Delete</Button
                        >
                    </div>
                </div>
                <div v-else class="font-bold text-gray-600">
                    You don't have an education history
                </div>
            </div>
            <div
                class="container relative flex max-h-[35em] max-w-lg flex-col gap-8 overflow-visible rounded bg-white p-8 shadow"
            >
                <h2 class="text-lg font-bold uppercase text-black/60">
                    Edit Education History
                </h2>

                <div class="flex flex-col gap-4 overflow-visible">
                    <div class="flex gap-4">
                        <Input
                            v-model="form.institution"
                            :error="form.errors.institution"
                            label="institution"
                            type="text"
                            name="institution"
                            :options="{ leftIcon: PhoneIcon, size: 'small' }"
                        />
                        <Input
                            v-model="form.degree"
                            :error="form.errors.degree"
                            label="Degree"
                            type="text"
                            name="degree"
                            :options="{ leftIcon: UserIcon, size: 'small' }"
                        />
                    </div>
                    <Input
                        v-model="form.field_of_study"
                        :error="form.errors.field_of_study"
                        label="Field of study"
                        type="text"
                        name="field_of_study"
                        :options="{ leftIcon: UserIcon, size: 'small' }"
                    />
                    <DateTime
                        v-model="startDateComputed"
                        class=""
                        label="Start Date"
                        :dateOptions="{
                            minDate: new Date(1900, 0, 0),
                            maxDate: new Date(2025, 0, 0),
                        }"
                        :options="{ leftIcon: CalendarDaysIcon }"
                    ></DateTime>
                    <div class="flex flex-col items-center justify-center">
                        <DateTime
                            v-model="endDateComputed"
                            :disabled="toDate"
                            class=""
                            label="End Date"
                            :dateOptions="{
                                minDate: new Date(1900, 0, 0),
                                maxDate: new Date(2025, 0, 0),
                            }"
                            :options="{ leftIcon: CalendarDaysIcon }"
                        />
                        <Checkbox v-model="toDate" label="To date" />
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
                        :options="{ color: 'green', shape: 'pill' }"
                        >Add Job</Button
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
                        >Cancel</Button
                    >
                    <Button
                        @click="
                            () => {
                                editMode ? submitEdit() : submitAdd();
                            }
                        "
                        class="w-full"
                        :options="{ color: 'green', shape: 'pill' }"
                        >Edit Job</Button
                    >
                </div>
            </div>
        </div>
    </Teleport>
</template>
