<script setup>
import { Avatar, Button } from '@/Components/UI';
import { PencilSquareIcon } from '@heroicons/vue/24/outline';
const props = defineProps({
    edit: {
        type: Boolean,
        default: false,
    },
    user: {
        type: Object,
        required: true,
    },
    openModal: {
        type: Function,
        default: () => {},
    },
});
</script>

<template>
    <div
        class="relative container bg-white w-full md:w-[15em] flex flex-col justify-between p-6 rounded shadow-md overflow-hidden group"
    >
        <Button
            v-if="edit"
            class="absolute top-0 right-0 m-4 z-10"
            :options="{ leftIcon: PencilSquareIcon }"
            @click="openModal('avatar')"
        ></Button>
        <div class="flex flex-col items-center gap-2 relative">
            <Avatar
                size="2xl"
                :src="
                    $page.props.auth.user.avatar
                        ? '/avatars/users/' + $page.props.auth.user.avatar
                        : null
                "
            />
            <h2 class="text-lg uppercase font-bold text-center">
                {{ user.name }}
            </h2>
            <div class="flex items-center">
                <h2 class="font-bold text-center text-black/60">
                    {{ user.tag }}
                </h2>
                <Button
                    @click="openModal('user')"
                    v-if="edit"
                    class="m-1 scale-75"
                    :options="{ leftIcon: PencilSquareIcon }"
                ></Button>
            </div>
        </div>
    </div>
</template>
