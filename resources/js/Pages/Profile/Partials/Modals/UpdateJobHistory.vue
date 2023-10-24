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

const submitAdd = () => {
    form.post(route('profile.update.jobHistory'), {
        onSuccess: (response) => {
            jobHistory.value = response.props.jobHistory;
            editMode.value = false;
            form.company = '';
            form.title = '';
            form.description = '';
            startDateComputed.value = null;
            endDateComputed.value = null;
            toDate.value = false;
        },
        onFinish: () => {
            // props.closeModal();
        },
    });
};

const submitEdit = () => {
    form.put(route('profile.update.jobHistory'), {
        onSuccess: (response) => {
            jobHistory.value = response.props.jobHistory;
            editMode.value = false;
            form.id = null;
            form.company = '';
            form.title = '';
            form.description = '';
            startDateComputed.value = null;
            endDateComputed.value = null;
            toDate.value = false;
        },
        onFinish: () => {
            // props.closeModal();
        },
    });
};

const submitDelete = () => {
    form.delete(route('profile.update.jobHistory'), {
        onSuccess: (response) => {
            jobHistory.value = response.props.jobHistory;
            editMode.value = false;
            form.id = null;
            form.company = '';
            form.title = '';
            form.description = '';
            startDateComputed.value = null;
            endDateComputed.value = null;
            toDate.value = false;
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
            class="fixed inset-0 flex md:flex-row flex-col items-center justify-center gap-4 z-50 overflow-auto"
        >
            <!-- Backdrop -->
            <div
                @click="closeModal()"
                class="absolute inset-0 bg-black opacity-50 h-full"
            ></div>
            <div
                class="relative container flex flex-col bg-white p-8 rounded shadow max-w-lg max-h-[calc(100vh-2rem)] overflow-y-auto"
            >
                <div
                    v-if="jobHistory.length !== 0"
                    v-for="job in jobHistory"
                    :key="job.id"
                    class="flex justify-between items-center pb-4 pt-4 first:pt-0 last:pb-0 last:border-b-0 border-b-2"
                >
                    <div>
                        <div class="text-lg font-bold">
                            {{ job.company }}
                            <span class="text-base text-gray-600">{{
                                job.title
                            }}</span>
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
                class="relative container flex flex-col gap-8 bg-white p-8 rounded shadow max-w-lg max-h-[35em] overflow-visible"
            >
                <h2 class="text-lg font-bold uppercase text-black/60">
                    Edit Job History
                </h2>

                <div class="overflow-visible flex flex-col gap-4">
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
                        :dateOptions="{
                            minDate: new Date(1900, 0, 0),
                            maxDate: new Date(2025, 0, 0),
                        }"
                        :options="{ leftIcon: CalendarDaysIcon }"
                    ></DateTime>
                    <div class="flex items-center justify-center flex-col">
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
                <div v-if="!editMode" class="w-full flex items-center">
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
                <div v-else class="w-full flex items-center gap-4">
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
        </div>
    </Teleport>
</template>
