<script setup>
import Login from './Partials/Login.vue';
import Register from './Partials/Register.vue';
</script>
<template>
    <div
        class="container bg-white p-4 rounded-md shadow-md flex-col-reverse items-center md:flex-row max-w-screen-md flex gap-4 mx-32 mt-24 md:items-start justify-between"
    >
        <Register></Register>
        <Login></Login>
    </div>
</template>
