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
        class="container flex max-w-md flex-col gap-4 px-4 py-2"
    >
        <h2 class="w-full text-2xl font-bold text-gray-700">
            {{ $t('generic.why_create_account') }}
            <ul class="list-inside list-disc text-sm">
                <li>{{ $t('generic.save_jobs') }}</li>
                <li>{{ $t('generic.apply_jobs') }}</li>
                <li>{{ $t('generic.save_time') }}</li>
            </ul>
        </h2>
        <h2 class="w-full text-2xl font-bold text-gray-700">
            {{ $t('generic.want_account') }}
        </h2>
        <Input
            v-model="form.name"
            :error="form.errors.name"
            :label="$t('labels.name')"
            type="text"
            name="name"
            :options="{ leftIcon: UserIcon }"
        />
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
        <Input
            v-model="form.password_confirmation"
            :error="form.errors.password_confirmation"
            :label="$t('labels.confirm_password')"
            type="password"
            name="password_confirmation"
            :options="{ leftIcon: KeyIcon }"
        />
        <Checkbox
            v-model="agreement"
            :label="$t('labels.terms_and_conditions')"
            color="gray"
        />
        <Button :disabled="!agreement">{{ $t('labels.register') }}</Button>
    </form>
</template>
