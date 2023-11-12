<script setup>
import { Button } from '@/Components/UI';
import Input from '@/Components/UI/Input/Input.vue';
import { Head, useForm } from '@inertiajs/vue3';

// Assuming the token is passed as a prop to this component
const props = defineProps({
    token: String,
});

const form = useForm({
    email: '',
    password: '',
    password_confirmation: '',
    token: props.token,
});

const submit = () => {
    form.post(route('password.store'), {
        onFinish: () => {
            form.reset();
        },
    });
};
</script>

<template>
    <Head title="Reset password" />

    <div
        class="container mt-12 w-full max-w-screen-sm rounded bg-zinc-800 p-4 text-zinc-100 shadow"
    >
        <h1 class="text-xl font-bold">Reset Your Password</h1>
        <form @submit.prevent="submit" class="mt-6 flex flex-col gap-4">
            <Input
                label="Email"
                v-model="form.email"
                name="email"
                :error="form.errors.email"
            />
            <Input
                label="New Password"
                v-model="form.password"
                name="new_password"
                type="password"
                :error="form.errors.password"
            />
            <Input
                label="Confirm New Password"
                v-model="form.password_confirmation"
                type="password"
                name="password_confirmation"
                :error="form.errors.password_confirmation"
            />
            <Button>Reset Password</Button>
        </form>
    </div>
</template>
