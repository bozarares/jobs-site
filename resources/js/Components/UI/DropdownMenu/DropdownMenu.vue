<script setup>
import { cva } from 'class-variance-authority';
import { Teleport } from 'vue';
import { onBeforeMount } from 'vue';
import { provide } from 'vue';
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
const isSmallScreen = ref(window.innerWidth < 640);

const updateScreenSize = () => {
    isSmallScreen.value = window.innerWidth < 640;
};

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
provide('closeDropdown', closeMenu);

onMounted(() => {
    window.addEventListener('resize', updateScreenSize);
});

onBeforeUnmount(() => {
    document.removeEventListener('click', handleOutsideClick);
});

const menuClass = computed(() => {
    return cva(
        'absolute z-10 mt-2 flex flex-col items-center rounded border-[1px] border-zinc-700/20 bg-white shadow',
        {
            variants: {
                align: {
                    left: 'sm:left-0 sm:origin-top-left',
                    right: 'sm:right-0 sm:origin-top-right',
                },
            },
        },
    )({ align: props.align });
});

const handleOutsideClick = (event) => {
    if (!menuRef.value || !buttonRef.value) return;

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
            enter-active-class="transition-all duration-75"
            enter-to-class="scale-100 opacity-100"
            leave-from-class="scale-100 opacity-100"
            leave-active-class="transition-all duration-75"
            leave-to-class="scale-90 opacity-0"
            mode="out-in"
        >
            <Teleport to="header" v-if="isSmallScreen && menuIsVisible">
                <div
                    ref="menuRef"
                    :class="[
                        menuClass,
                        props.class,
                        'fixed inset-0 flex h-fit items-center justify-center',
                    ]"
                >
                    <slot />
                </div>
            </Teleport>
            <div
                v-else-if="menuIsVisible"
                ref="menuRef"
                :class="[menuClass, props.class]"
            >
                <slot />
            </div>
        </Transition>
    </div>
</template>

<style scoped>
@media (max-width: 768px) {
    .fixed {
        top: 50%;
        left: 50%;
        transform: translate(-50%, 7%);
    }
}
</style>
