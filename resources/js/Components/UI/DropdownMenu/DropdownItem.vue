<template>
    <component
        @click="props.autoClose ? triggerClose() : null"
        :method="props.method"
        :as="props.as"
        :is="props.is"
        :href="props.href"
        :class="dropdownItemClass"
    >
        <component
            class="absolute left-0 mx-2 h-6 w-6 text-zinc-700/60 dark:text-inherit"
            :is="leftIcon"
        />
        <div class="inline-block w-full text-center">
            <slot />
        </div>
        <component
            class="absolute right-0 mx-2 h-6 w-6 text-zinc-700/60 dark:text-inherit"
            :is="rightIcon"
        />
    </component>
</template>

<script setup>
import { cva } from 'class-variance-authority';
import { inject } from 'vue';
import { onMounted } from 'vue';
import { computed, ref } from 'vue';

const props = defineProps({
    leftIcon: {
        type: [Object, Function],
    },
    rightIcon: {
        type: [Object, Function],
    },
    is: {
        type: [String, Object],
        default: 'button',
    },
    as: {
        type: [String],
        default: 'button',
    },
    method: { type: String },
    size: {
        type: String,
        default: 'normal',
        validator: (val) => ['normal', 'xl', 'xxl'].includes(val),
    },
    href: String,
    class: {
        type: String,
        default: '',
    },
    autoClose: {
        type: Boolean,
        default: true,
    },
});

const triggerClose = ref();
onMounted(() => {
    triggerClose.value = inject('closeDropdown');
});

const dropdownItemClass = computed(() => {
    return cva(
        'relative flex w-full cursor-pointer select-none items-center tracking-wider transition-colors hover:bg-zinc-200' +
            ' ' +
            props.class,
        {
            variants: {
                size: { normal: 'h-[2.5em]', xl: 'h-[3em]', xxl: 'h-[4em]' },
            },
        },
    )({ size: props.size });
});
</script>
