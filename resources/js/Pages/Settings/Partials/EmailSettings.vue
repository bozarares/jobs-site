<script setup>
import { Button, Input } from '@/Components/UI';
import { usePage } from '@inertiajs/vue3';

const page = usePage();
const user = page.props.auth.user;
</script>

<template>
    <h2 class="text-2xl font-extrabold">Account Settings</h2>
    <div class="flex flex-col items-center justify-center gap-4">
        <div
            class="mt-4 flex w-[20em] flex-col items-center justify-center gap-2"
        >
            <h2 class="w-full text-xl font-bold">Change Email</h2>
            <h3 class="w-full text-sm font-bold">
                {{ user.email }}
                <span :class="{ 'text-green-400': user.email_verified_at }">{{
                    user.email_verified_at ? 'Verified' : 'Verify your email'
                }}</span>
            </h3>
            <Input label="New email" />
            <Button class="w-full">Change Email</Button>
        </div>
    </div>
</template>
