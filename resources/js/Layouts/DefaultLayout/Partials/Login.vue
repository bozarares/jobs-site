<script setup>
import { Button, Checkbox, DropdownHeader } from '@/Components/UI';
import Input from '@/Components/UI/Input/Input.vue';
import {
    EyeIcon,
    EyeSlashIcon,
    KeyIcon,
    UserIcon,
} from '@heroicons/vue/24/outline';
import { Link, useForm } from '@inertiajs/vue3';

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
    <form @submit.prevent="submit" class="flex flex-col gap-4 p-4 w-full">
        <DropdownHeader>Login</DropdownHeader>
        <Input
            v-model="form.email"
            :error="form.errors.email"
            label="Email"
            type="email"
            name="email"
            :options="{ leftIcon: UserIcon, size: 'small' }"
        />
        <Input
            v-model="form.password"
            :error="form.errors.password"
            label="Password"
            type="password"
            name="password"
            :options="{
                size: 'small',
                borderStyle: 'bordered',
                leftIcon: KeyIcon,
                passwordIcon: {
                    show: EyeIcon,
                    hide: EyeSlashIcon,
                },
            }"
        />
        <Checkbox label="Remember me" color="gray" v-model="form.remember" />
        <Button type="submit">Login</Button>
        <div class="flex flex-col items-center gap-1 text-sm">
            <Link href="/" class="text-blue-600 hover:text-blue-800"
                >Forgot your password?</Link
            >
            <Link
                :href="route('connect')"
                class="text-blue-600 hover:text-blue-800"
                >I want an account!</Link
            >
        </div>
    </form>
</template>
