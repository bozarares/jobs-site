<script setup>
import { ref } from 'vue';
import RatingStars from './RatingStars.vue';
import { HandThumbDownIcon, HandThumbUpIcon } from '@heroicons/vue/24/outline';
import Button from './UI/Button/Button.vue';
const featured = ref(false);
</script>

<template>
    <div
        class="group container relative flex h-[20em] w-full cursor-pointer flex-col justify-between overflow-hidden rounded-lg bg-white p-6 shadow-md transition duration-300 hover:scale-[1.02] hover:shadow-lg sm:h-[25em] sm:w-[18em] md:h-[20em] md:w-[15em]"
        :class="
            featured
                ? 'outline outline-2 outline-yellow-500'
                : 'outline outline-gray-200'
        "
    >
        <!-- Logo and Company Name -->
        <div class="mb-4 flex flex-col items-center">
            <img
                class="h-8 fill-current object-contain text-gray-500"
                src="/images/logo/logo-black.png"
                alt="Logo"
            />
            <div class="text-sm text-gray-500">Interactive Software SRL</div>
            <RatingStars :rating.number="3.5"></RatingStars>
            <!-- Job Title -->
            <h2 class="mb-2 mt-8 text-center text-xl font-bold">
                Programator .net senior
            </h2>
        </div>

        <!-- Location and Salary -->
        <div class="mt-4 flex items-center justify-between">
            <div class="flex flex-col items-center text-sm">
                <span class="font-bold">LOCATE </span> București
            </div>
            <div class="flex flex-col items-center text-sm">
                <span class="font-bold">SALARIU</span> 2750 - 3000 €
            </div>
        </div>
        <div
            class="h-18 absolute bottom-0 left-0 flex w-full translate-y-24 items-center justify-center rounded-b-md border-t-2 bg-white transition-transform duration-300 group-hover:translate-y-0"
        >
            <div class="flex items-center justify-center gap-4">
                <Button :options="{ shape: 'pill', color: 'green' }"
                    >Apply</Button
                >
                <div class="flex h-20 w-20 items-center gap-4">
                    <HandThumbDownIcon />
                    <HandThumbUpIcon />
                </div>
            </div>
        </div>
    </div>
</template>
