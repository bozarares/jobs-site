<script setup>
import { useForm, usePage } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';
import { Button, Input, SelectInput } from '@/Components/UI';
import { debounce } from 'lodash';
import axios from 'axios';
import { XMarkIcon } from '@heroicons/vue/24/outline';
import { watch } from 'vue';
import { computed } from 'vue';
import { onBeforeUnmount } from 'vue';

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
    <Teleport to="body">
        <div
            class="fixed inset-0 z-50 flex flex-col items-center justify-center gap-4 overflow-auto md:flex-row"
        >
            <!-- Backdrop -->
            <div
                @click="closeModal()"
                class="absolute inset-0 bg-black opacity-50"
            ></div>
            <div
                class="container relative ml-auto mr-auto flex max-h-[25em] max-w-xs flex-col overflow-auto rounded bg-white p-8 shadow md:mr-0"
            >
                <div
                    v-if="skills.length !== 0"
                    v-for="skill in skills"
                    :key="skill.id"
                    class="flex items-center justify-between border-b-2 pb-4 pt-4 first:pt-0 last:border-b-0 last:pb-0"
                >
                    <div>{{ skill }}</div>
                    <XMarkIcon
                        class="w-5"
                        @click="
                            () => {
                                skills = skills.filter(
                                    (item) => item !== skill,
                                );
                            }
                        "
                    />
                </div>
                <div v-else class="font-bold text-gray-600">
                    You don't have selected skills
                </div>
            </div>
            <div
                class="container relative ml-auto mr-auto flex max-h-[35em] max-w-lg flex-col gap-8 overflow-auto rounded bg-white p-8 shadow md:ml-0"
            >
                <h2 class="text-lg font-bold uppercase text-black/60">
                    Edit Skills
                </h2>

                <div class="flex gap-2 overflow-auto">
                    <Input label="Skills" v-model="inputTextValue" />
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
                            if (skills.length === 0) {
                                return;
                            }
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
        </div>
    </Teleport>
</template>
