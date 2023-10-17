<script setup>
import { Checkbox, Input, Button } from '@/Components/UI';
import { AtSymbolIcon, KeyIcon, UserIcon } from '@heroicons/vue/24/outline';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const agreement = ref(false);

const submit = () => {
    form.post(route('register'), {
        onBefore: () => {
            form.clearErrors();
            if (!agreement.value) return false;
        },
        onFinish: () => {
            form.reset('password');
            form.reset('password_confirmation');
        },
    });
};
</script>

<template>
    <form
        id="connect-register"
        @submit.prevent="submit"
        class="container flex flex-col max-w-md px-4 py-2 gap-4"
    >
        <h2 class="w-full text-2xl text-gray-700 font-bold">
            Why create an account?
            <ul class="text-sm list-disc list-inside">
                <li>You can save jobs</li>
                <li>You can apply to jobs</li>
                <li>You will be in touch with the employer</li>
            </ul>
        </h2>
        <h2 class="w-full text-2xl text-gray-700 font-bold">
            I want an account
        </h2>
        <Input
            v-model="form.name"
            :error="form.errors.name"
            label="Name"
            type="text"
            name="name"
            :options="{ leftIcon: UserIcon }"
        />
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
        <Input
            v-model="form.password_confirmation"
            :error="form.errors.password_confirmation"
            label="Password Confirmation"
            type="password"
            name="password_confirmation"
            :options="{ leftIcon: KeyIcon }"
        />
        <Checkbox
            v-model="agreement"
            label="I agree with terms and conditions"
            color="gray"
        />
        <Button :disabled="!agreement">Register</Button>
    </form>
</template>
