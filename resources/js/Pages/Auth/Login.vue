<script setup>
import Login from './Partials/Login.vue';
import Register from './Partials/Register.vue';

const props = defineProps({
    status: {
        type: String,
        default: '',
    },
});
</script>
<template>
    <div
        class="container mx-32 mt-24 flex max-w-screen-md flex-col-reverse items-center justify-between gap-4 rounded-md bg-white p-4 text-zinc-800 shadow-md dark:bg-zinc-800 dark:text-zinc-100 md:flex-row md:items-start"
    >
        <Register></Register>
        <Login :status="status"></Login>
    </div>
</template>
