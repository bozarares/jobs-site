<script setup>
import { Button, Checkbox, Input } from '@/Components/UI';
import { AtSymbolIcon, KeyIcon, UserIcon } from '@heroicons/vue/24/outline';
import { useForm } from '@inertiajs/vue3';

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => {
            form.reset('password');
        },
    });
};
</script>

<template>
    <form
        @submit.prevent="submit"
        class="container flex flex-col max-w-md px-4 py-2 gap-4"
    >
        <h2 class="w-full text-2xl text-gray-700 font-bold">
            I have an account
        </h2>
        <Input
            v-model="form.email"
            :error="form.errors.email"
            label="Email"
            type="text"
            name="email"
            :options="{ leftIcon: AtSymbolIcon }"
        />
        <Input
            v-model="form.password"
            :error="form.errors.password"
            label="Password"
            type="password"
            name="password"
            :options="{ leftIcon: KeyIcon }"
        />
        <Checkbox label="Remember me" />
        <Button type="submit">Login</Button>
    </form>
</template>
