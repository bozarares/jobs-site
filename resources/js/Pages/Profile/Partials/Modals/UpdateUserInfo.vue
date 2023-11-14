<script setup>
// TODO: Change form with axios
// TODO: Change controller so it will send a response

import { useForm } from '@inertiajs/vue3';
import { Button, Input } from '@/Components/UI';
import { UserIcon, XMarkIcon } from '@heroicons/vue/24/outline';
import { useCurrentUser } from '@/Composables/useCurrentUser';

const props = defineProps({
    closeModal: { type: Function, default: () => {} },
});

const currentUser = useCurrentUser();

const form = useForm({
    name: currentUser.value.name,
    tag: currentUser.value.tag,
});

const submit = () => {
    form.post(route('profile.update.user'), {
        onFinish: () => {
            props.closeModal();
        },
    });
};
</script>

<template>
    <div
        id="user-modal"
        class="container relative mx-auto flex max-h-[35em] max-w-lg flex-col gap-8 overflow-auto rounded bg-white p-8 shadow dark:bg-zinc-800"
    >
        <div class="flex items-center justify-between">
            <h2
                class="text-lg font-bold uppercase text-zinc-500 dark:text-zinc-300"
            >
                Edit User
            </h2>
            <XMarkIcon
                class="h-6 cursor-pointer text-zinc-500 dark:text-zinc-300"
                @click="closeModal()"
            />
        </div>

        <div class="flex flex-col gap-4 overflow-auto">
            <Input
                v-model="form.name"
                :error="form.errors.name"
                :label="$t('labels.name')"
                type="text"
                name="name"
                :options="{
                    leftIcon: UserIcon,
                    size: 'small',
                    borderStyle: 'border-bottom',
                }"
            />
            <Input
                v-model="form.tag"
                :error="form.errors.tag"
                :label="$t('labels.tag')"
                type="text"
                name="tag"
                :options="{
                    leftIcon: UserIcon,
                    size: 'small',
                    borderStyle: 'border-bottom',
                }"
            />
        </div>
        <Button
            @click="submit"
            class=""
            :options="{ color: 'blue', shape: 'pill' }"
            >Save</Button
        >
    </div>
</template>
