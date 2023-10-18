<script setup>
import Navbar from './Partials/Navbar.vue';
import Footer from './Partials/Footer.vue';

const props = defineProps({ auth: Object });
</script>

<template>
    <Navbar></Navbar>
    <main class="flex-grow flex flex-col items-center min-h-screen">
        <slot />
    </main>
    <Footer></Footer>
</template>
