<script setup>
import { Button, Input } from '@/Components/UI';
import { KeyIcon } from '@heroicons/vue/24/outline';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    user: Object,
});

const passwordForm = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const submit = async () => {
    passwordForm.put(route('password.update'), {
        onFinish: () => {
            passwordForm.reset();
        },
    });
};
</script>

<template>
    <h2 class="text-2xl font-extrabold">
        {{ $t('sections.settings.privacy') }}
    </h2>
    <div class="flex flex-col justify-center gap-4">
        <div class="mt-4 flex flex-col items-start justify-center gap-2">
            <h2 class="w-full text-xl font-bold">
                {{ $t('labels.password.change') }}
            </h2>
            <Input
                v-model="passwordForm.current_password"
                :error="passwordForm.errors.current_password"
                :label="$t('labels.password.current')"
                type="password"
                name="current_password"
                :options="{ leftIcon: KeyIcon }"
            />
            <Input
                v-model="passwordForm.password"
                :error="passwordForm.errors.password"
                :label="$t('labels.password.new')"
                type="password"
                name="new_password"
                :options="{ leftIcon: KeyIcon }"
            />
            <Input
                v-model="passwordForm.password_confirmation"
                :error="passwordForm.errors.password_confirmation"
                :label="$t('labels.password.confirmNew')"
                type="password"
                name="password_confirmation"
                :options="{ leftIcon: KeyIcon }"
            />
            <Button
                class="w-full"
                @click="
                    () => {
                        submit();
                    }
                "
                >{{ $t('labels.password.change') }}</Button
            >
        </div>
    </div>
</template>
