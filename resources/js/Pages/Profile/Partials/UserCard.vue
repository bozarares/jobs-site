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
        class="group container relative flex w-full flex-col justify-between overflow-hidden rounded bg-white p-6 shadow-md md:w-[15em]"
    >
        <Button
            v-if="edit"
            class="absolute right-0 top-0 z-10 m-4"
            :options="{ leftIcon: PencilSquareIcon }"
            @click="openModal('avatar')"
        ></Button>
        <div class="relative flex flex-col items-center gap-2">
            <Avatar
                size="2xl"
                :src="
                    $page.props.auth.user.avatar
                        ? '/users/avatars/' +
                          $page.props.auth.user.avatar +
                          '.' +
                          $page.props.auth.user.avatar_extension
                        : null
                "
            />
            <h2 class="text-center text-lg font-bold uppercase">
                {{ user.name }}
            </h2>
            <div class="flex items-center">
                <h2 class="text-center font-bold text-black/60">
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
