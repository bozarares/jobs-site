<script setup>
import { HandThumbDownIcon, HandThumbUpIcon } from '@heroicons/vue/24/outline';
import { Button } from '@/Components/UI';
import { Link } from '@inertiajs/vue3';
import { PlusIcon } from '@heroicons/vue/24/solid';
</script>

<template>
    <div
        class="container flex min-h-[5em] cursor-pointer flex-col items-center justify-center rounded-sm bg-zinc-50 px-4 py-2 outline outline-zinc-200 transition-all duration-150 ease-in-out hover:scale-[101%] hover:bg-white dark:bg-zinc-800 dark:text-zinc-100 dark:outline-zinc-700 lg:min-h-[15em] lg:max-w-[13em]"
    >
        <PlusIcon
            class="w-20 stroke-gray-300 stroke-1 font-bold text-zinc-300"
        />
    </div>
</template>
