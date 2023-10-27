<script setup>
// TODO: Change form with axios
// TODO: Change controller so it will send a response

import { onMounted, ref, watch, computed, onBeforeUnmount } from 'vue';
import { useForm, usePage } from '@inertiajs/vue3';
import { Button, Input } from '@/Components/UI';
import { debounce } from 'lodash';
import axios from 'axios';
import { XMarkIcon } from '@heroicons/vue/24/outline';

const props = defineProps({
    closeModal: { type: Function, default: () => {} },
});
const inputTextValue = ref('');
const searchSkillsArray = ref([]);

const page = usePage();
const user = page.props.auth.user;

const initialSkills = ref([...user.skills.map((skill) => skill.name)]);
const skills = ref(user.skills.map((skill) => skill.name));

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
    form.post(route('profile.update.skills'), {
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
        class="container relative flex max-h-[25em] max-w-lg flex-col overflow-y-auto rounded bg-white p-8 shadow"
    >
        <div
            v-if="skills.length !== 0"
            v-for="skill in skills"
            :key="skill.id"
            class="flex items-center justify-between border-b-2 pb-4 pt-4 first:pt-0 last:border-b-0 last:pb-0"
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
                <XMarkIcon class="h-5 text-black/60" />
            </div>
        </div>
        <div v-else class="font-bold text-gray-600">
            You don't have selected skills
        </div>
    </div>
    <div
        class="container relative mx-auto flex max-h-[35em] max-w-lg flex-col gap-8 overflow-auto rounded bg-white p-8 shadow"
    >
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-bold uppercase text-black/60">
                Edit Skills
            </h2>
            <div>
                <XMarkIcon
                    class="h-6 cursor-pointer text-black/60"
                    @click="closeModal()"
                />
            </div>
        </div>

        <div class="flex gap-2 overflow-auto">
            <Input label="Skills" v-model="inputTextValue" name="add_skill" />
            <Button
                @click="
                    () => {
                        skills.push(inputTextValue);
                        inputTextValue = '';
                    }
                "
                >Add</Button
            >
        </div>
        <div class="flex gap-2">
            <div
                v-for="skill in filteredSkills"
                :key="skill.id"
                class="cursor-pointer rounded bg-gray-800 px-2 py-1 text-white hover:bg-gray-700"
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
            :options="{ color: 'green', shape: 'pill' }"
            >Save</Button
        >
    </div>
</template>
