<script setup>
import { useForm, usePage } from '@inertiajs/vue3';
import { Button } from '@/Components/UI';
import Input from '@/Components/UI/Input/Input.vue';
import { UserIcon } from '@heroicons/vue/24/outline';

const props = defineProps({
    closeModal: { type: Function, default: () => {} },
});

const page = usePage();
const user = page.props.auth.user;

const form = useForm({
    name: user.name,
    tag: user.tag,
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
        class="container relative mx-auto flex max-h-[35em] max-w-lg flex-col gap-8 overflow-auto rounded bg-white p-8 shadow"
    >
        <h2 class="text-lg font-bold uppercase text-black/60">Edit User</h2>

        <div class="flex flex-col gap-4 overflow-auto">
            <Input
                v-model="form.name"
                :error="form.errors.name"
                label="Name"
                type="text"
                name="name"
                :options="{ leftIcon: UserIcon, size: 'small' }"
            />
            <Input
                v-model="form.tag"
                :error="form.errors.tag"
                label="Tag"
                type="text"
                name="tag"
                :options="{ leftIcon: UserIcon, size: 'small' }"
            />
        </div>
        <Button
            @click="submit"
            class=""
            :options="{ color: 'green', shape: 'pill' }"
            >Save</Button
        >
    </div>
</template>
