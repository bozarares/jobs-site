<script setup>
import mdiAt from '~icons/mdi/at';
import mdiKey from '~icons/mdi/key';
import mdiEye from '~icons/mdi/eye';
import mdiEyeOff from '~icons/mdi/eye-off';
import mdiAccount from '~icons/mdi/account';
import { useForm } from '@inertiajs/vue3';

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
        <h2 class="w-full text-2xl font-bold">
            {{ $t('messages.whyCreateAccount.self') }}
            <ul class="list-inside list-disc text-sm">
                <li>{{ $t('messages.whyCreateAccount.messageOne') }}</li>
                <li>{{ $t('messages.whyCreateAccount.messageTwo') }}</li>
                <li>{{ $t('messages.whyCreateAccount.messageThree') }}</li>
            </ul>
        </h2>
        <h2 class="w-full text-2xl font-bold">
            {{ $t('labels.account.want') }}
        </h2>
        <Input
            v-model="form.name"
            :error="form.errors.name"
            :label="$t('labels.name')"
            type="text"
            name="name"
            :options="{
                leftIcon: mdiAccount,
                borderStyle: 'border-bottom',
            }"
        />
        <Input
            v-model="form.email"
            :error="form.errors.email"
            :label="$t('labels.email.self')"
            type="text"
            name="email"
            :options="{
                leftIcon: mdiAt,
                borderStyle: 'border-bottom',
            }"
        />
        <Input
            v-model="form.password"
            :error="form.errors.password"
            :label="$t('labels.password.self')"
            type="password"
            name="password"
            :options="{
                leftIcon: mdiKey,
                borderStyle: 'border-bottom',
                passwordIcon: {
                    show: mdiEye,
                    hide: mdiEyeOff,
                },
            }"
        />
        <Input
            v-model="form.password_confirmation"
            :error="form.errors.password_confirmation"
            :label="$t('labels.password.confirm')"
            type="password"
            name="password_confirmation"
            :options="{
                leftIcon: mdiKey,
                borderStyle: 'border-bottom',
                passwordIcon: {
                    show: mdiEye,
                    hide: mdiEyeOff,
                },
            }"
        />
        <Checkbox
            v-model="agreement"
            :label="$t('labels.account.terms')"
            color="gray"
        />
        <Button :disabled="!agreement">{{ $t('common.register') }}</Button>
    </form>
</template>
