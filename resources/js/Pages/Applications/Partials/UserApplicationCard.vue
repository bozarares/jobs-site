<script setup>
const props = defineProps({
    user: {
        type: Object,
        required: true,
    },
});
</script>

<template>
    <div class="flex h-auto justify-around">
        <div class="flex flex-col items-center">
            <Avatar
                :src="
                    user?.avatar
                        ? '/users/avatars/' +
                          user.avatar +
                          '.' +
                          user.avatar_extension
                        : null
                "
                size="2xl"
                :name="user?.name || ''"
            />
            <div class="flex flex-col items-center">
                <div class="text-center text-2xl font-bold">
                    {{ user?.name }}
                </div>
                <div class="text-center font-bold">
                    {{ user?.tag }}
                </div>
            </div>
        </div>
        <div class="flex flex-col items-center justify-center gap-4">
            <div class="flex flex-col items-center">
                <div>{{ user?.email }}</div>
                <div>{{ user?.phone_number }}</div>
            </div>
            <div class="flex flex-col">
                <a
                    :href="`https://linkedin.com/in/${user?.social_linkedin}`"
                    v-if="user?.social_linkedin"
                    class="flex items-center gap-2 text-center font-bold"
                    target="_blank"
                    rel="noopener noreferrer"
                >
                    <span class="text-blue-600"><Mdi:Linkedin /></span>
                    LinkedIn
                </a>
                <a
                    :href="`https://github.com/${user?.social_github}`"
                    v-if="user?.social_github"
                    class="flex items-center gap-2 text-center font-bold"
                    target="_blank"
                    rel="noopener noreferrer"
                >
                    <span><Mdi:Github class="dark:text-zinc-100" /></span>
                    Github
                </a>
                <a
                    :href="`https://facebook.com/${user?.social_facebook}`"
                    v-if="user?.social_facebook"
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
