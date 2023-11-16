<script setup>
import Navbar from './Partials/Navbar.vue';
import Footer from './Partials/Footer.vue';
import ModalWrapper from '@/Components/ModalWrapper.vue';
import '@vueup/vue-quill/dist/vue-quill.snow.css';
import { useCookieStore } from '@/Stores/cookieStore';

const cookieStore = useCookieStore();

watch(
    () => cookieStore.theme,
    (newValue) => {
        if (newValue === 'dark') document.documentElement.classList.add('dark');
        else document.documentElement.classList.remove('dark');
    },
    { immediate: true },
);

onMounted(() => {
    const setFavicon = (darkMode) => {
        const link = document.createElement('link');
        link.rel = 'icon';
        link.href = darkMode
            ? '/favicon/favicon-white.png'
            : '/favicon/favicon-black.png';
        document.head.appendChild(link);
    };

    const darkModeMediaQuery = window.matchMedia(
        '(prefers-color-scheme: dark)',
    );
    setFavicon(darkModeMediaQuery.matches);

    darkModeMediaQuery.addEventListener('change', (e) => {
        setFavicon(e.matches);
    });
});
</script>

<template>
    <Navbar></Navbar>
    <main class="flex min-h-screen flex-grow flex-col items-center pt-[56px]">
        <slot />
    </main>
    <Footer></Footer>
    <ModalWrapper />
</template>

<style>
/* width */
::-webkit-scrollbar {
    width: 10px;
}

/* Track */
::-webkit-scrollbar-track {
    @apply bg-transparent;
}

/* Handle */
::-webkit-scrollbar-thumb {
    @apply rounded-full bg-zinc-600 shadow dark:bg-zinc-200;
}

/* Handle on hover */
::-webkit-scrollbar-thumb:hover {
    @apply bg-zinc-700 dark:bg-zinc-300;
}
</style>
