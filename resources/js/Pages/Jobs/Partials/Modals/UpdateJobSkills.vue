<script setup>
// TODO: Change form with axios
// TODO: Change controller so it will send a response

import { useForm, usePage } from '@inertiajs/vue3';
import pkg from 'lodash';
const { debounce } = pkg;
import axios from 'axios';

const props = defineProps({
    closeModal: { type: Function, default: () => {} },
});
const inputTextValue = ref('');
const searchSkillsArray = ref([]);

const page = usePage();
const job = page.props.job;

const initialSkills = ref([...job.skills.map((skill) => skill.name)]);
const skills = ref(job.skills.map((skill) => skill.name));

const form = useForm({
    skills: skills.value,
});

const filteredSkills = computed(() => {
    return searchSkillsArray.value.filter(
        (skill) => !skills.value.includes(skill.name),
    );
});

const submit = () => {
    if (JSON.stringify(initialSkills.value) === JSON.stringify(skills.value)) {
        props.closeModal();
        return;
    }
    form.skills = skills.value;
    form.post(route('job.update.skills', { job: job.slug }), {
        preserveScroll: true,

        onFinish: () => {
            props.closeModal();
        },
    });
};

const searchSkills = debounce((value) => {
    axios.post(route('get.skills'), { skill: value }).then((response) => {
        searchSkillsArray.value = response.data;
    });
}, 500);

onBeforeUnmount(() => {
    searchSkills.cancel();
});

watch(
    () => inputTextValue.value,
    (newValue, oldValue) => {
        searchSkills(newValue);
    },
);

onMounted(() => {
    searchSkills('');
});
</script>

<template>
    <div
        id="skills-modal"
        class="container relative flex max-h-[25em] max-w-lg flex-col overflow-y-auto rounded bg-white p-8 shadow dark:bg-zinc-800 dark:text-zinc-100"
    >
        <div
            v-if="skills.length !== 0"
            v-for="skill in skills"
            :key="skill.id"
            class="flex items-center justify-between border-b-2 pb-4 pt-4 first:pt-0 last:border-b-0 last:pb-0 dark:border-b-zinc-500"
        >
            <div>{{ skill }}</div>
            <div
                class="cursor-pointer"
                aria-label="Delete button"
                @click="
                    () => {
                        skills = skills.filter((item) => item !== skill);
                    }
                "
            >
                <Heroicons:xMark class="h-5" />
            </div>
        </div>
        <div v-else class="font-bold">
            {{ $t('fallbacks.skills') }}
        </div>
    </div>
    <div
        class="container relative mx-auto flex max-h-[35em] max-w-lg flex-col gap-8 overflow-auto rounded bg-white p-8 shadow dark:bg-zinc-800 dark:text-zinc-100"
    >
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-bold uppercase">
                {{ $t('labels.skills.editRequired') }}
            </h2>
            <div>
                <Heroicons:xMark
                    class="h-6 cursor-pointer"
                    @click="closeModal()"
                />
            </div>
        </div>

        <div class="flex gap-2 overflow-auto">
            <Input
                :label="$tc('labels.skills.self', 1)"
                v-model="inputTextValue"
                name="add_skill"
            />
            <Button
                @click="
                    () => {
                        skills.push(inputTextValue);
                        inputTextValue = '';
                    }
                "
                >{{ $t('common.add') }}</Button
            >
        </div>
        <div class="flex flex-wrap gap-2">
            <div
                v-for="skill in filteredSkills"
                :key="skill.id"
                class="cursor-pointer select-none rounded-full px-3 py-1 outline outline-zinc-400 transition-all duration-150 ease-in-out hover:scale-105"
            >
                <div
                    @click="
                        () => {
                            skills.push(skill.name);
                        }
                    "
                >
                    {{ skill.name }}
                </div>
            </div>
        </div>
        <Button
            @click="
                () => {
                    // if (
                    //     JSON.stringify(initialSkills.value) ===
                    //     JSON.stringify(skills.value)
                    // ) {
                    //     return;
                    // }
                    if (inputTextValue !== '') {
                        skills.push(inputTextValue);
                    }
                    submit();
                }
            "
            class=""
            :options="{ color: 'blue', shape: 'pill' }"
            >{{ $t('common.save') }}</Button
        >
    </div>
</template>
