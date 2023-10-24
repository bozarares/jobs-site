<script setup>
import { useForm, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
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
    <Teleport to="body">
        <div class="fixed inset-0 flex items-center justify-center z-50">
            <!-- Backdrop -->
            <div
                @click="closeModal()"
                class="absolute inset-0 bg-black opacity-50"
            ></div>
            <div
                class="relative container flex flex-col gap-8 bg-white p-8 rounded shadow max-w-lg mx-auto overflow-auto max-h-[35em]"
            >
                <h2 class="text-lg font-bold uppercase text-black/60">
                    Edit User
                </h2>

                <div class="overflow-auto flex flex-col gap-4">
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
        </div>
    </Teleport>
</template>
