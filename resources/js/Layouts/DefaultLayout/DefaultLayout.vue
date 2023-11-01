<script setup>
import Navbar from './Partials/Navbar.vue';
import Footer from './Partials/Footer.vue';
import ModalWrapper from '@/Components/ModalWrapper.vue';
import '@vueup/vue-quill/dist/vue-quill.snow.css';

const props = defineProps({ auth: Object });
</script>

<template>
    <Navbar></Navbar>
    <main class="flex min-h-screen flex-grow flex-col items-center">
        <slot />
    </main>
    <Footer></Footer>
    <ModalWrapper />
</template>
