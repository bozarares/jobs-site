<script setup>
import { cva } from 'class-variance-authority';
import { computed, onBeforeUnmount, ref } from 'vue';

const props = defineProps({
    label: String,
    align: {
        type: String,
        default: 'left',
        validator: (val) => ['left', 'right'].includes(val),
    },
    class: String,
});
const menuRef = ref(null);
const buttonRef = ref(null);
const menuIsVisible = ref(false);
const toggleMenu = () => {
    if (!menuIsVisible.value) {
        menuIsVisible.value = true;
        document.addEventListener('click', handleOutsideClick);
    } else {
        menuIsVisible.value = false;
        document.removeEventListener('click', handleOutsideClick);
    }
};

const closeMenu = () => {
    if (menuIsVisible.value) {
        menuIsVisible.value = false;
        document.removeEventListener('click', handleOutsideClick);
    }
};

onBeforeUnmount(() => {
    document.removeEventListener('click', handleOutsideClick);
});

const menuClass = computed(() => {
    return cva(
        'absolute z-10 mt-2 flex flex-col items-center rounded border-[1px] border-gray-700/20 bg-white shadow',
        {
            variants: {
                align: {
                    left: 'left-0 origin-top-left',
                    right: 'right-0 origin-top-right',
                },
            },
        },
    )({ align: props.align });
});

const handleOutsideClick = (event) => {
    if (!menuRef.value || !buttonRef.value) return; // Early return if refs are null

    if (
        !menuRef.value.contains(event.target) &&
        !buttonRef.value.contains(event.target)
    ) {
        toggleMenu();
    }
};
</script>

<template>
    <div @keydown.escape="closeMenu" class="relative">
        <div @click="toggleMenu" ref="buttonRef">
            <slot name="dropdownMenuButton" />
        </div>
        <Transition
            enter-from-class="scale-90 opacity-0"
            enter-active-class="transition-all duration-100"
            enter-to-class="scale-100 opacity-100"
            leave-from-class="scale-100 opacity-100"
            leave-active-class="transition-all duration-100"
            leave-to-class="scale-90 opacity-0"
            mode="out-in"
        >
            <div
                ref="menuRef"
                v-if="menuIsVisible"
                :class="[menuClass, props.class]"
            >
                <slot />
            </div>
        </Transition>
    </div>
</template>
