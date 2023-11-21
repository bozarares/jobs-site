<script setup>
import ShowPasswordIcon from '../Icons/ShowPasswordIcon.vue';
import HidePasswordIcon from '../Icons/HidePasswordIcon.vue';
import { computed, ref, watch, onMounted, onUnmounted } from 'vue';
import { cva } from 'class-variance-authority';

const props = defineProps({
    label: {
        type: String,
        required: true,
    },
    type: String,
    name: String,
    error: {
        type: String,
        default: null,
    },
    disabled: Boolean,
    options: {
        type: Object,
        default: () => ({}),
    },
    modelValue: [String, Number],
});

const emits = defineEmits(['update:modelValue']);
const isLabelFloating = ref(false);

const options = computed(() => {
    const defaultOptions = {
        borderStyle: 'bordered',
        background: 'default',
        leftIcon: null,
        passwordIcon: { show: ShowPasswordIcon, hide: HidePasswordIcon },
        size: 'default',
    };
    return { ...defaultOptions, ...props.options };
});

// Ref for input v-model (for persistent floating label)
const inputTextValue = ref(props.modelValue ? props.modelValue : '');

// Check if input is focused (for floating label)
const isFocused = ref(false);

// Ref for inputType (for Eye Icon)
const inputType = ref(props.type);
const containerElementRef = ref(null);

onMounted(() => {
    document.addEventListener('mousedown', handleOutsideClick);
    if (props.modelValue && props.modelValue !== '') {
        inputTextValue.value = props.modelValue;
        isLabelFloating.value = true;
    }
});

// On component unmount, remove the mousedown event listener.
onUnmounted(() => {
    document.removeEventListener('mousedown', handleOutsideClick);
});

const handleOutsideClick = (event) => {
    if (!containerElementRef.value.contains(event.target)) {
        if (!isFocused.value && inputTextValue.value.trim() === '') {
            isLabelFloating.value = false;
        }
    }
};
watch(
    () => props.modelValue,
    (newValue) => {
        inputTextValue.value = newValue;
        if (newValue === '' && isFocused.value === false) {
            isLabelFloating.value = false;
        }
        if (newValue !== '') {
            isLabelFloating.value = true;
        }
    },
);

watch(
    () => inputTextValue.value,
    (newValue) => {
        if (newValue === '' && isFocused.value === false) {
            isLabelFloating.value = false;
        }
        emits('update:modelValue', newValue);
    },
);

// Random input id, preventing clashes
const inputId = computed(() => props.name + '-' + crypto.randomUUID());

// A CVA instance for borderStyle variants
const inputClass = computed(() =>
    cva(
        'w-full flex-shrink-0 pl-12 tracking-wider caret-gray-700 outline-none transition-all duration-300 ease-in-out  dark:text-zinc-100 dark:caret-zinc-200',
        {
            variants: {
                background: {
                    default: 'dark:bg-zinc-800',
                    none: 'bg-transparent dark:bg-transparent',
                },
                borderStyle: {
                    bordered:
                        'rounded-md border-2 border-zinc-200 shadow focus:border-blue-500 focus:outline-none focus:ring-0 dark:border-zinc-500 focus:dark:border-blue-500',
                    'border-bottom':
                        'border-0 border-b-2 focus:border-blue-500 focus:outline-none focus:ring-0 ',
                    'no-border': '',
                },
                disabled: {
                    true: '!cursor-not-allowed !bg-zinc-100 !text-zinc-400 dark:!bg-zinc-700 dark:!text-zinc-100 ',
                },
                password: {
                    true: 'pr-12',
                },
                size: {
                    default: 'text-md py-3',
                    small: 'py-2 text-sm',
                },
                error: {
                    true: '!border-red-500 dark:!border-red-500',
                },
            },
        },
    )({
        background: options.value.background,
        borderStyle: options.value.borderStyle,
        disabled: props.disabled,
        password: props.type === 'password',
        size: options.value.size,
        error: !!props.error,
    }),
);

const labelClasses = computed(() => {
    let baseClasses = '';
    if (options.value.size === 'default') {
        baseClasses += isLabelFloating.value
            ? '-translate-y-4 transform text-xs'
            : 'transform-none text-md';
    }
    if (options.value.size === 'small') {
        baseClasses += isLabelFloating.value
            ? '-translate-y-1/2 transform opacity-0 text-xs'
            : 'transform-none text-sm';
    }
    return baseClasses;
});

const ariaLabel = computed(() => {
    return (props.name ? props.name : props.type) + ' input';
});

const handleInputFocus = () => {
    isFocused.value = true;
    if (inputTextValue.value === '') isLabelFloating.value = true;
};
const handleInputBlur = () => {
    isFocused.value = false;
    if (inputTextValue.value === '') isLabelFloating.value = false;
};

const togglePasswordVisibility = () => {
    inputType.value = inputType.value === 'password' ? 'text' : 'password';
};
</script>

<template>
    <div class="flex w-full flex-col">
        <div
            ref="containerElementRef"
            class="flex-grow-1 relative flex w-full flex-col items-center tracking-wider text-zinc-700"
        >
            <!-- Left Icon -->
            <div
                class="absolute left-0 ml-2 flex h-full w-8 items-center justify-center"
                aria-hidden="true"
            >
                <component
                    class="h-inherit pointer-events-none absolute select-none text-zinc-900 dark:text-zinc-400"
                    :is="options.leftIcon"
                />
            </div>
            <!-- Input -->
            <input
                :class="[inputClass, !options.leftIcon && '!pr-0 pl-6']"
                :type="inputType"
                :name="props.name"
                :id="inputId"
                v-model="inputTextValue"
                :disabled="props.disabled"
                :aria-label="ariaLabel"
                :aria-invalid="props.error ? true : false"
                :aria-errormessage="props.error ? props.error : null"
                :aria-labelledby="`${inputId}-label`"
                @focus="handleInputFocus"
                @blur="handleInputBlur"
            />
            <!-- Floating Label -->
            <div
                class="pointer-events-none absolute right-0 flex h-full w-full items-center justify-center"
            >
                <span
                    class="text-md pointer-events-none absolute left-12 select-none text-zinc-600 transition-all duration-300 dark:text-zinc-100"
                    :class="labelClasses"
                    :id="`${inputId}-label`"
                    >{{ props.label }}</span
                >
            </div>
            <!-- Password EyeIcon/EyeSlashIcon -->
            <button
                type="button"
                v-if="props.type === 'password'"
                class="absolute right-0 mr-2 flex h-full w-8 items-center justify-center"
                @click.prevent.stop="togglePasswordVisibility"
                :aria-label="
                    inputType === 'password' ? 'Show password' : 'Hide password'
                "
            >
                <component
                    class="absolute right-0 flex h-8 w-full cursor-pointer select-none items-center justify-center p-1 text-zinc-900 transition-all duration-300 dark:text-zinc-400"
                    :is="
                        inputType === 'password'
                            ? options.passwordIcon.show
                            : options.passwordIcon.hide
                    "
                />
            </button>
        </div>
        <!-- Errors -->
        <div
            v-if="props.error"
            class="w-full px-1 pt-1 text-sm font-medium text-red-500"
            :class="[!props.error && 'invisible']"
        >
            {{ props.error }}
        </div>
    </div>
</template>

<style scoped></style>
