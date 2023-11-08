<script setup>
import Navbar from './Partials/Navbar.vue';
import Footer from './Partials/Footer.vue';
import ModalWrapper from '@/Components/ModalWrapper.vue';
import '@vueup/vue-quill/dist/vue-quill.snow.css';
import { useCookieStore } from '@/Stores/cookieStore';
import { watch } from 'vue';

const props = defineProps({ auth: Object });
const cookieStore = useCookieStore();

watch(
    () => cookieStore.theme,
    (newValue) => {
        if (newValue === 'dark') document.documentElement.classList.add('dark');
        else document.documentElement.classList.remove('dark');
    },
    { immediate: true },
);

watch(
    () => props.auth?.user,
    (newValue) => {
        console.log(newValue);
        if (newValue) cookieStore.theme = newValue.theme;
    },
);
</script>

<template>
    <Navbar></Navbar>
    <main class="flex min-h-screen flex-grow flex-col items-center">
        <slot />
    </main>
    <Footer></Footer>
    <ModalWrapper />
</template>
