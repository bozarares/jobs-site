<script setup>
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps({
    status: {
        type: String,
        default: '',
    },
});

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('password.email'), {
        onFinish: () => {
            form.reset();
        },
    });
};
</script>
<template>
    <Head title="Forgot password" />

    <div
        class="container mt-12 w-full max-w-screen-sm rounded bg-zinc-800 p-4 text-zinc-100 shadow"
    >
        <h1 class="text-xl font-bold">Forgot Your Password?</h1>
        <h2>No problem, we got you!</h2>
        <form
            id="connect-forgot-password"
            @submit.prevent="submit"
            class="flex flex-col gap-4"
        >
            <Input
                label="Email Address"
                v-model="form.email"
                :error="form.errors.email"
            />
            <Button>Reset Password</Button>
            <h3 class="text-center text-sm text-zinc-100">{{ status }}</h3>
        </form>
    </div>
</template>
