<script setup>
import { Avatar, Button } from '@/Components/UI';
import FacebookIcon from '@/Components/UI/Icons/FacebookIcon.vue';
import GithubIcon from '@/Components/UI/Icons/GithubIcon.vue';
import LinkedinIcon from '@/Components/UI/Icons/LinkedinIcon.vue';
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
            @click="openModal('socials')"
            v-if="edit"
            class="absolute top-0 right-0 m-4 z-10"
            :options="{ leftIcon: PencilSquareIcon }"
        ></Button>
        <div class="flex flex-col items-center gap-2 relative">
            <h2 class="text-lg font-bold uppercase text-black/60">Socials</h2>
            <h2 class="font-bold text-center text-black/60">
                {{ user.email }}
            </h2>
            <h2
                v-if="user.phone_number"
                class="font-bold text-center text-black/60"
            >
                {{ user.phone_number }}
            </h2>
            <div class="flex flex-col gap-2 mt-4">
                <a
                    :href="`https://linkedin.com/in/${user.social_linkedin}`"
                    v-if="user.social_linkedin"
                    class="font-bold text-center text-black/60 flex items-center gap-2"
                    target="_blank"
                    rel="noopener noreferrer"
                >
                    <span class="fill-blue-700"><LinkedinIcon /></span>
                    LinkedIn
                </a>
                <a
                    :href="`https://github.com/${user.social_github}`"
                    v-if="user.social_github"
                    class="font-bold text-center text-black/60 flex items-center gap-2"
                    target="_blank"
                    rel="noopener noreferrer"
                >
                    <span><GithubIcon /></span>

                    Github
                </a>
                <a
                    :href="`https://facebook.com/${user.social_facebook}`"
                    v-if="user.social_facebook"
                    class="font-bold text-center text-black/60 flex items-center gap-2"
                    target="_blank"
                    rel="noopener noreferrer"
                >
                    <span class="fill-blue-600"><FacebookIcon /></span>

                    Facebook
                </a>
            </div>
        </div>
    </div>
</template>
