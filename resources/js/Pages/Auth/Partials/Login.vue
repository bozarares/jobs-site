<script setup>
import mdiAt from '~icons/mdi/at';
import mdiKey from '~icons/mdi/key';
import mdiEye from '~icons/mdi/eye';
import mdiEyeOff from '~icons/mdi/eye-off';
import { Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    status: {
        type: String,
        default: '',
    },
});

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
        <h2 class="w-full text-2xl font-bold">
            {{ $t('labels.account.have') }}
        </h2>
        <Input
            v-model="form.email"
            :error="form.errors.email"
            :label="$t('labels.email.self')"
            type="text"
            name="email"
            :options="{ leftIcon: mdiAt, borderStyle: 'border-bottom' }"
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
        <Checkbox :label="$t('labels.account.remember')" />
        <Button type="submit">{{ $t('common.login') }}</Button>
        <div class="flex flex-col items-center gap-1 text-sm">
            <Link
                :href="route('password.request')"
                class="text-blue-600 hover:text-blue-800"
                >{{ $t('labels.password.forgot') }}</Link
            >
        </div>
        <h3 class="text-center text-sm text-zinc-100">{{ status }}</h3>
    </form>
</template>
