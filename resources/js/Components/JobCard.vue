<script setup>
import { ref } from 'vue';
import RatingStars from './RatingStars.vue';
import { HandThumbDownIcon, HandThumbUpIcon } from '@heroicons/vue/24/outline';
import Button from './UI/Button/Button.vue';
const featured = ref(false);
</script>

<template>
    <div
        class="group relative container bg-white w-full h-[20em] sm:w-[18em] sm:h-[25em] md:w-[15em] md:h-[20em] flex flex-col justify-between p-6 rounded-lg shadow-md hover:shadow-lg transition overflow-hidden hover:scale-[1.02] duration-300 cursor-pointer"
        :class="
            featured
                ? 'outline-yellow-500 outline outline-2'
                : 'outline outline-gray-200'
        "
    >
        <!-- Logo and Company Name -->
        <div class="flex flex-col items-center mb-4">
            <img
                class="h-8 fill-current object-contain text-gray-500"
                src="/images/logo/logo-black.png"
                alt="Logo"
            />
            <div class="text-sm text-gray-500">Interactive Software SRL</div>
            <RatingStars :rating.number="3.5"></RatingStars>
            <!-- Job Title -->
            <h2 class="text-xl font-bold mb-2 mt-8 text-center">
                Programator .net senior
            </h2>
        </div>

        <!-- Location and Salary -->
        <div class="flex items-center justify-between mt-4">
            <div class="text-sm flex flex-col items-center">
                <span class="font-bold">LOCATE </span> București
            </div>
            <div class="text-sm flex flex-col items-center">
                <span class="font-bold">SALARIU</span> 2750 - 3000 €
            </div>
        </div>
        <div
            class="absolute bottom-0 left-0 w-full h-18 bg-white flex items-center justify-center border-t-2 rounded-b-md translate-y-24 group-hover:translate-y-0 transition-transform duration-300"
        >
            <div class="flex gap-4 items-center justify-center">
                <Button :options="{ shape: 'pill', color: 'green' }"
                    >Apply</Button
                >
                <div class="h-20 w-20 flex items-center gap-4">
                    <HandThumbDownIcon />
                    <HandThumbUpIcon />
                </div>
            </div>
        </div>
    </div>
</template>
