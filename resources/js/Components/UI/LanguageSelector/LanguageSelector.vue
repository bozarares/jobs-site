<script setup>
import { onUnmounted, ref } from 'vue';
import { useFloating } from '@floating-ui/vue';

const props = defineProps({
    name: String,
    disabled: Boolean,
    languages: Array, // Array of language objects
    modelValue: String, // The selected language locale
});

const emits = defineEmits(['update:modelValue']);

const selectedLanguage = ref(
    props.languages.find((lang) => lang.locale === props.modelValue),
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
    <div ref="containerElementRef" class="relative">
        <div
            class="cursor-pointer select-none text-2xl"
            @click="toggleSelectVisibility"
        >
            {{ selectedLanguage?.emoji || 'Select Language' }}
        </div>

        <div
            v-if="isSelectVisible"
            ref="selectElementRef"
            class="absolute z-10 w-auto"
            :style="selectFloatingStyles"
        >
            <ul class="rounded border bg-white shadow">
                <li
                    v-for="language in languages"
                    :key="language.locale"
                    class="flex cursor-pointer items-center justify-center gap-2 p-2 hover:bg-gray-100"
                    @click="handleLanguageSelect(language)"
                >
                    <div>{{ language.emoji }}</div>
                    <div>{{ language.name }}</div>
                </li>
            </ul>
        </div>
    </div>
</template>
