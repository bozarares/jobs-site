<script setup>
import { onUnmounted, ref, markRaw } from 'vue';
import { useFloating } from '@floating-ui/vue';
import { shallowRef } from 'vue';

const props = defineProps({
    name: String,
    disabled: Boolean,
    languages: Array,
    modelValue: String,
    showName: {
        type: Boolean,
        default: true,
    },
    class: {
        type: String,
        default: '',
    },
});

const emits = defineEmits(['update:modelValue']);

const selectedLanguage = shallowRef(
    props.languages.find((lang) => lang.locale === props.modelValue),
);

watch(
    () => props.modelValue,
    (newValue) => {
        selectedLanguage.value = props.languages.find(
            (lang) => lang.locale === newValue,
        );
    },
);

const isSelectVisible = ref(false);
const containerElementRef = ref(null);
const selectElementRef = ref(null);

// Floating UI hook for positioning the dropdown
const { floatingStyles: selectFloatingStyles } = useFloating(
    containerElementRef,
    selectElementRef,
    { placement: 'bottom' },
);

const handleLanguageSelect = (language) => {
    selectedLanguage.value = language;
    emits('update:modelValue', language.locale);
    isSelectVisible.value = false;
};

const toggleSelectVisibility = () => {
    if (!props.disabled) {
        isSelectVisible.value = !isSelectVisible.value;
    }
};

// Hide the select when clicking outside
const handleClickOutside = (event) => {
    if (!containerElementRef.value.contains(event.target)) {
        isSelectVisible.value = false;
    }
};

// Setup click outside listener
window.addEventListener('click', handleClickOutside);

onUnmounted(() => {
    window.removeEventListener('click', handleClickOutside);
});
</script>

<template>
    <div
        ref="containerElementRef"
        class="flex w-full items-center justify-center"
        :class="props.class"
    >
        <div
            class="flex cursor-pointer select-none items-center gap-2"
            @click="toggleSelectVisibility"
        >
            <component :is="selectedLanguage?.flag" class="mr-2 h-6 w-6" />
            <div v-if="props.showName">
                {{ selectedLanguage?.name || 'Select Language' }}
            </div>
        </div>

        <div
            v-if="isSelectVisible"
            ref="selectElementRef"
            class="absolute z-10 w-auto"
            :style="selectFloatingStyles"
        >
            <ul
                class="rounded border bg-white shadow dark:border-zinc-700 dark:bg-zinc-800"
            >
                <li
                    v-for="language in languages"
                    :key="language.locale"
                    class="flex cursor-pointer items-center justify-center gap-2 p-2 hover:bg-zinc-100 dark:hover:bg-zinc-700"
                    @click="handleLanguageSelect(language)"
                >
                    <component :is="language.flag" class="h-6 w-6" />
                    <div>{{ language.name }}</div>
                </li>
            </ul>
        </div>
    </div>
</template>
