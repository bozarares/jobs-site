<script setup>
import { PlusIcon } from '@heroicons/vue/24/solid';
import { Link } from '@inertiajs/vue3';
</script>
<template>
    <Link
        :href="route('companies.create')"
        class="group container relative flex w-full cursor-pointer flex-col items-center justify-center overflow-hidden rounded bg-zinc-50 p-6 shadow-md transition-all duration-150 ease-in-out hover:scale-[101%] hover:bg-white dark:bg-zinc-800 dark:hover:bg-zinc-800/95 md:w-[15em]"
    >
        <!-- Logo and Company Name -->

        <PlusIcon
            class="w-20 stroke-gray-300 stroke-1 font-bold text-zinc-300"
        />
    </Link>
</template>
