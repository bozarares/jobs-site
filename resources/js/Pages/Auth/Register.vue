<script setup>
import { Button, Input } from '@/Components/UI';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import {
    EyeIcon,
    EyeSlashIcon,
    KeyIcon,
    UserIcon,
    AtSymbolIcon,
} from '@heroicons/vue/24/outline';

const loading = ref(false);
const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    loading.value = true;

    form.post(route('register'), {
        onFinish: () => {
            loading.value = false;
            form.reset('password', 'password_confirmation');
        },
    });
};
</script>

<template>
    <Head title="Register" />
    <div
        class="flex min-h-screen flex-col items-center bg-gray-100 pt-2 sm:pt-8"
    >
        <form
            @submit.prevent="submit"
            class="relative flex w-[30rem] flex-col items-center gap-8 overflow-hidden rounded-md border-2 bg-white pb-4 shadow-sm"
        >
            <div class="relative w-full">
                <img
                    class="pointer-events-none h-40 w-full select-none object-cover"
                    src="/images/register.jpg"
                    alt="Login image"
                />
                <div
                    class="pointer-events-none absolute left-1/2 top-1/4 flex w-1/2 -translate-x-1/2 -translate-y-1/2 transform select-none items-center justify-center gap-2 rounded bg-white bg-opacity-90 fill-white px-2 py-1 text-center text-xl font-bold shadow-lg"
                >
                    Sign up to
                    <img
                        class="h-8"
                        src="/images/logo/logo-no-background.svg"
                        alt=""
                    />
                </div>
            </div>
            <div class="flex w-full flex-col items-center gap-8 px-8">
                <Input
                    v-model="form.name"
                    label="Name"
                    type="text"
                    name="name"
                    :error="form.errors.name"
                    :options="{ borderStyle: 'bordered', leftIcon: UserIcon }"
                ></Input>
                <Input
                    v-model="form.email"
                    label="Email"
                    type="email"
                    name="email"
                    :error="form.errors.email"
                    :options="{
                        borderStyle: 'bordered',
                        leftIcon: AtSymbolIcon,
                    }"
                ></Input>
                <Input
                    v-model="form.password"
                    label="Password"
                    type="password"
                    name="password"
                    :error="form.errors.password"
                    :options="{
                        borderStyle: 'bordered',
                        leftIcon: KeyIcon,
                        passwordIcon: { show: EyeIcon, hide: EyeSlashIcon },
                    }"
                ></Input>
                <Input
                    v-model="form.password_confirmation"
                    label="Password Confirmation"
                    type="password"
                    name="password_confirmation"
                    :error="form.errors.password"
                    :options="{
                        borderStyle: 'bordered',
                        leftIcon: KeyIcon,
                        passwordIcon: { show: EyeIcon, hide: EyeSlashIcon },
                    }"
                ></Input>
                <Button class="h-6 w-full px-4 py-6" :loading="!!loading"
                    >Sign up with email</Button
                >
                <Link :href="route('login')"
                    >You already have and accout? Sign in!</Link
                >
            </div>
        </form>
    </div>
</template>
