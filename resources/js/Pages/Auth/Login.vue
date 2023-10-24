<script setup>
import Login from './Partials/Login.vue';
import Register from './Partials/Register.vue';
</script>
<template>
    <div
        class="container mx-32 mt-24 flex max-w-screen-md flex-col-reverse items-center justify-between gap-4 rounded-md bg-white p-4 shadow-md md:flex-row md:items-start"
    >
        <Register></Register>
        <Login></Login>
    </div>
</template>
