<script setup>
import { useCookieStore } from '@/Stores/cookieStore';
import { Link } from '@inertiajs/vue3';

const cookieStore = useCookieStore();
</script>

<template>
    <header
        class="z-50 mt-12 w-full items-center bg-white py-2 shadow dark:bg-zinc-800 dark:text-zinc-100 dark:shadow-zinc-700"
    >
        <div
            class="container mx-auto flex max-w-screen-lg items-center justify-between px-4 py-12"
        >
            <div class="flex flex-col items-center">
                <Link href="/">
                    <img
                        class="h-8 fill-current object-contain text-zinc-500"
                        :src="
                            cookieStore.theme === 'dark'
                                ? '/images/logo/logo-white.png'
                                : '/images/logo/logo-black.png'
                        "
                        alt="Logo"
                    />
                </Link>
                <h2
                    class="gradient bg-gradient-to-r from-blue-300 to-blue-500 bg-clip-text pb-2 text-lg font-bold text-transparent"
                >
                    A portofolio site by Rareș-Ionuț Boza
                </h2>
            </div>
            <div class="flex items-center gap-1">
                <h2>Some Icons by</h2>
                <a target="_blank" href="https://icons8.com">Icons8</a>
            </div>
        </div>
    </header>
</template>
