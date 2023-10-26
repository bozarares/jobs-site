<script setup>
import { useForm, usePage } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import { Button, Checkbox, DateTime, Input } from '@/Components/UI';
import {
    CalendarDaysIcon,
    PhoneIcon,
    UserIcon,
    XMarkIcon,
} from '@heroicons/vue/24/outline';
import dayjs from 'dayjs';

const props = defineProps({
    closeModal: { type: Function, default: () => {} },
});

const page = usePage();
const jobHistory = ref(page.props.user.job_history);

const toDate = ref(false);
const form = useForm({
    id: null,
    company: '',
    title: '',
    description: '',
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
    form.post(route('profile.update.jobHistory'), {
        onSuccess: (response) => {
            jobHistory.value = page.props.user.job_history;
            resetForm();
        },
        onFinish: () => {},
    });
};

const submitEdit = () => {
    form.put(route('profile.update.jobHistory'), {
        onSuccess: (response) => {
            jobHistory.value = page.props.user.job_history;
            editMode.value = false;
            resetForm();
        },
        onFinish: () => {},
    });
};

const submitDelete = () => {
    form.delete(route('profile.update.jobHistory'), {
        onSuccess: (response) => {
            jobHistory.value = page.props.user.job_history;
            editMode.value = false;
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
        id="job-history-modal"
        class="container relative flex max-h-[25em] max-w-lg flex-col overflow-y-auto rounded bg-white p-8 shadow"
    >
        <div
            v-if="jobHistory.length !== 0"
            v-for="job in jobHistory"
            :key="job.id"
            class="flex items-center justify-between border-b-2 pb-4 pt-4 first:pt-0 last:border-b-0 last:pb-0"
        >
            <div>
                <div class="text-lg font-bold">
                    {{ job.company }}
                    <span class="text-base text-gray-600">{{ job.title }}</span>
                </div>
                <div>{{ job.description }}</div>
                <div class="text-sm text-gray-600">
                    {{ job.start_date }} -
                    {{ job.end_date ? job.end_date : 'To date' }}
                </div>
            </div>
            <div class="flex gap-2">
                <Button
                    @click="
                        () => {
                            editMode = true;
                            form.id = job.id;
                            form.company = job.company;
                            form.title = job.title;
                            form.description = job.description;
                            startDateComputed = job.start_date;
                            endDateComputed = job.end_date;

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
                            form.id = job.id;
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
            You don't have a job history
        </div>
    </div>
    <div
        class="container relative flex max-h-[35em] max-w-lg flex-col gap-8 overflow-visible rounded bg-white p-8 shadow"
    >
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-bold uppercase text-black/60">
                Edit Job History
            </h2>
            <XMarkIcon
                class="h-6 cursor-pointer text-black/60"
                @click="closeModal()"
            />
        </div>

        <div class="flex flex-col gap-4 overflow-visible">
            <div class="flex gap-4">
                <Input
                    v-model="form.company"
                    :error="form.errors.company"
                    label="Company"
                    type="text"
                    name="company"
                    :options="{ leftIcon: PhoneIcon, size: 'small' }"
                />
                <Input
                    v-model="form.title"
                    :error="form.errors.title"
                    label="Title"
                    type="text"
                    name="title"
                    :options="{ leftIcon: UserIcon, size: 'small' }"
                />
            </div>
            <Input
                v-model="form.description"
                :error="form.errors.description"
                label="Description"
                type="text"
                name="description"
                :options="{ leftIcon: UserIcon, size: 'small' }"
            />
            <DateTime
                v-model="startDateComputed"
                class=""
                label="Start Date"
                name="start_date"
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
                    name="end_date"
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
                        form.company = '';
                        form.title = '';
                        form.description = '';
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
</template>
