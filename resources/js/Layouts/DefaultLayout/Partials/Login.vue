<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import mdiAt from '~icons/mdi/at';
import mdiKey from '~icons/mdi/key';
import mdiEye from '~icons/mdi/eye';
import mdiEyeOff from '~icons/mdi/eye-off';
const triggerClose = ref();
onMounted(() => {
    triggerClose.value = inject('closeDropdown');
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onSuccess: () => {
            triggerClose.value();
        },
        onFinish: () => {
            form.reset('password');
        },
    });
};
</script>
<template>
    <form
        id="navbar-login"
        @submit.prevent="submit"
        class="flex w-full flex-col gap-4 p-4"
    >
        <DropdownHeader>{{ $t('common.login') }}</DropdownHeader>
        <Input
            v-model="form.email"
            :error="form.errors.email"
            :label="$t('labels.email.self')"
            type="email"
            name="email"
            :options="{
                leftIcon: mdiAt,
                size: 'small',
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
                size: 'small',
                borderStyle: 'border-bottom',
                leftIcon: mdiKey,
                passwordIcon: {
                    show: mdiEye,
                    hide: mdiEyeOff,
                },
            }"
        />
        <Checkbox
            :label="$t('labels.account.remember')"
            color="gray"
            v-model="form.remember"
        />
        <Button type="submit">{{ $t('common.login') }}</Button>
        <div class="flex flex-col items-center gap-1 text-sm">
            <Link
                @click.prevent="triggerClose()"
                :href="route('password.request')"
                class="text-blue-600 hover:text-blue-800"
                >{{ $t('labels.password.forgot') }}</Link
            >
            <Link
                @click.prevent="triggerClose()"
                :href="route('connect')"
                class="text-blue-600 hover:text-blue-800"
                >{{ $t('labels.account.want') }}</Link
            >
        </div>
    </form>
</template>
