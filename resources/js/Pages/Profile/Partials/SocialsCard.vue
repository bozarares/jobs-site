<script setup>
import { useModalStore } from '@/Stores/modalStore';

const modalStore = useModalStore();
const props = defineProps({
    edit: {
        type: Boolean,
        default: false,
    },
    user: {
        type: Object,
        required: true,
    },
});
</script>

<template>
    <div
        :id="edit ? 'profile-socials-edit' : ''"
        @click="
            () => {
                if (edit) {
                    modalStore.openModal('userSocials');
                }
            }
        "
        class="group container relative flex w-full flex-col justify-between rounded bg-white p-6 text-zinc-600 shadow-md dark:bg-zinc-800 dark:text-zinc-100 md:w-[15em]"
        :class="{
            'cursor-pointer': edit,
        }"
    >
        <h2
            v-if="edit"
            class="absolute right-0 top-0 pr-2 text-xs font-extrabold text-zinc-500 transition-all duration-150 ease-in-out group-hover:text-zinc-600"
        >
            {{ $t('labels.clickToEdit.field') }}
        </h2>

        <div class="relative flex flex-col items-center gap-2">
            <h2 class="text-lg font-bold uppercase">
                {{ $t('labels.social.links') }}
            </h2>
            <h2 class="text-center font-bold">
                {{ user.email }}
            </h2>
            <h2 v-if="user.phone_number" class="text-center font-bold">
                {{ user.phone_number }}
            </h2>
            <div class="mt-4 flex flex-col gap-2">
                <a
                    :href="`https://linkedin.com/in/${user.social_linkedin}`"
                    v-if="user.social_linkedin"
                    class="flex items-center gap-2 text-center font-bold"
                    target="_blank"
                    rel="noopener noreferrer"
                >
                    <span class="text-blue-600"><Mdi:Linkedin /></span>
                    LinkedIn
                </a>
                <a
                    :href="`https://github.com/${user.social_github}`"
                    v-if="user.social_github"
                    class="flex items-center gap-2 text-center font-bold"
                    target="_blank"
                    rel="noopener noreferrer"
                >
                    <span class="dark:text-white"><Mdi:Github /></span>

                    Github
                </a>
                <a
                    :href="`https://facebook.com/${user.social_facebook}`"
                    v-if="user.social_facebook"
                    class="flex items-center gap-2 text-center font-bold"
                    target="_blank"
                    rel="noopener noreferrer"
                >
                    <span class="text-blue-600"><Mdi:Facebook /></span>

                    Facebook
                </a>
            </div>
        </div>
    </div>
</template>
