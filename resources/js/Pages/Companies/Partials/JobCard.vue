<script setup>
import { ref } from 'vue';
import { HandThumbDownIcon, HandThumbUpIcon } from '@heroicons/vue/24/outline';
import { Button } from '@/Components/UI';
const featured = ref(false);
</script>

<template>
    <div
        class="group container prose relative flex items-center justify-between rounded-sm px-4 outline outline-gray-200"
    >
        <div class="flex flex-col items-start">
            <div class="text-xl font-bold">Programator .net</div>
            <div class="text-xs font-bold">Internship, Entry level</div>
        </div>

        <div class="flex flex-col items-start justify-between gap-2 py-2">
            <div class="flex items-center gap-2 text-sm">
                <span class="font-bold uppercase">Location </span> București
            </div>
            <div class="flex items-center gap-2 text-sm">
                <span class="font-bold uppercase">Salary</span> 2750 - 3000 €
            </div>
        </div>
        <div
            class="absolute left-0 flex h-full w-full items-center justify-center gap-4 opacity-0 backdrop-blur-[2.5px] transition-all duration-200 ease-in-out group-hover:opacity-100"
        >
            <div class="flex gap-1">
                <Button
                    :options="{ leftIcon: HandThumbDownIcon, shape: 'pill' }"
                />
                <Button
                    :options="{ leftIcon: HandThumbUpIcon, shape: 'pill' }"
                />
            </div>
            <Button :options="{ color: 'green', shape: 'pill' }"
                >Go to job</Button
            >
        </div>
    </div>
</template>
