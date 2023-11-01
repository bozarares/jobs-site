<script setup>
import { router, useForm, usePage } from '@inertiajs/vue3';
import { onMounted, onBeforeMount, ref, reactive, markRaw } from 'vue';
import { Button, Checkbox, Input, Radio } from '@/Components/UI';
import toolbarOptions from '@/quillToolBarConfig';
import { XMarkIcon } from '@heroicons/vue/24/outline';
import axios from 'axios';

const quillRef = ref(null);
const page = usePage();
const company = page.props.company;

const job = reactive({
    title: '',
    type: 'remote',
    level: [],
    salary: '',
    location: '',
    description: '',
});

const salary_confidential = ref(false);

const props = defineProps({
    closeModal: { type: Function, default: () => {} },
});

const submit = () => {
    const htmlContent = quillRef.value.getHTML();
    job.description = htmlContent;
    axios
        .post(route('jobs.store', { company: company.slug }), job)
        .then((res) => {
            router.reload({ preserveState: true, only: ['company'] });
            props.closeModal();
        });
};

const isClient = ref(false);

onBeforeMount(() => {
    isClient.value = typeof window !== 'undefined';
});

let QuillEditor = ref(null);

onMounted(async () => {
    if (isClient.value) {
        const { QuillEditor: QuillImport } = await import('@vueup/vue-quill');
        QuillEditor.value = markRaw(QuillImport);
    }
});
</script>

<template>
    <div
        id="company-description-edit-modal"
        class="container relative mx-auto flex max-h-[50em] max-w-2xl flex-col gap-8 overflow-scroll rounded bg-white p-8 shadow"
    >
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-bold uppercase text-black/60">Add Job</h2>
            <XMarkIcon
                class="h-6 cursor-pointer text-black/60"
                @click="closeModal()"
            />
        </div>
        <div>
            <Input label="Job title" v-model="job.title" />
        </div>
        <div class="flex flex-wrap justify-center gap-12 md:flex-row">
            <div class="flex flex-col items-start">
                <h2 class="w-full text-center">Job Type</h2>
                <Radio
                    label="Remote"
                    name="type"
                    v-model="job.type"
                    value="remote"
                />
                <Radio
                    label="Hybrid"
                    name="type"
                    v-model="job.type"
                    value="hybrid"
                />
                <Radio
                    label="On-Site"
                    name="type"
                    v-model="job.type"
                    value="on-site"
                />
            </div>
            <div class="flex flex-col items-start">
                <h2 class="w-full text-center">Experience Level</h2>
                <Checkbox
                    label="Internship"
                    name="experience"
                    v-model="job.level"
                    value="internship"
                />
                <Checkbox
                    label="Junior"
                    name="experience"
                    v-model="job.level"
                    value="junior"
                />
                <Checkbox
                    label="Mid"
                    name="experience"
                    v-model="job.level"
                    value="mid"
                />
                <Checkbox
                    label="Senior"
                    name="experience"
                    v-model="job.level"
                    value="senior"
                />
            </div>
            <div class="flex w-full flex-col items-start gap-4 sm:w-auto">
                <Input
                    :disabled="salary_confidential"
                    label="Salary"
                    v-model.number="job.salary"
                />
                <Checkbox
                    label="Confidential"
                    name="type"
                    v-model="salary_confidential"
                    @change="
                        (e) => {
                            if (e.target.checked) {
                                job.salary = '';
                            }
                        }
                    "
                />
                <Input label="Town" v-model="job.location" />
            </div>
        </div>
        <div class="flex h-auto min-h-[15em] flex-col overflow-hidden pb-20">
            <QuillEditor
                v-if="isClient && QuillEditor"
                name="Company description"
                ref="quillRef"
                :toolbar="toolbarOptions"
                theme="snow"
            />
        </div>
        <Button
            @click="submit"
            class=""
            :options="{ color: 'green', shape: 'pill' }"
            >Save</Button
        >
    </div>
</template>
