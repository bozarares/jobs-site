<script setup>
import { Button, Checkbox, Input } from '@/Components/UI';
import { AtSymbolIcon, KeyIcon } from '@heroicons/vue/24/outline';
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
    <form
        id="connect-login"
        @submit.prevent="submit"
        class="container flex max-w-md flex-col gap-4 px-4 py-2"
    >
        <h2 class="w-full text-2xl font-bold text-gray-700">
            {{ $t('generic.have_account') }}
        </h2>
        <Input
            v-model="form.email"
            :error="form.errors.email"
            :label="$t('labels.email')"
            type="text"
            name="email"
            :options="{ leftIcon: AtSymbolIcon }"
        />
        <Input
            v-model="form.password"
            :error="form.errors.password"
            :label="$t('labels.password')"
            type="password"
            name="password"
            :options="{ leftIcon: KeyIcon }"
        />
        <Checkbox :label="$t('labels.remember_me')" />
        <Button type="submit">{{ $t('labels.login') }}</Button>
        <div class="flex flex-col items-center gap-1 text-sm">
            <Link href="/" class="text-blue-600 hover:text-blue-800">{{
                $t('labels.forgot_password')
            }}</Link>
            <Link
                :href="route('connect')"
                class="text-blue-600 hover:text-blue-800"
                >{{ $t('labels.want_account') }}</Link
            >
        </div>
    </form>
</template>
