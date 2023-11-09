<template>
    <div class="container mt-8 flex min-h-screen justify-center bg-zinc-100">
        <div :class="['p-6', props.class]">
            <div class="max-w-[50rem] rounded-md bg-white p-4 shadow-lg md:p-8">
                <slot></slot>
            </div>
        </div>
    </div>
</template>

<script setup>
const props = defineProps({ class: { type: String, default: '' } });
</script>
