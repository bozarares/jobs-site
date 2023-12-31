<script setup>
import { languages } from '@/Languages/languages';
import { useModalStore } from '@/Stores/modalStore';
import { useUserStore } from '@/Stores/userStore';

const modalStore = useModalStore();
const userStore = useUserStore();
const props = defineProps({
    edit: {
        type: Boolean,
        default: false,
    },
    user: {
        type: Object,
        required: true,
    },
    onLanguageChange: {
        type: Function,
        default: () => {},
    },
    language: {
        type: String,
        default: 'en',
    },
});

const language = ref(props.language);
watch(
    () => props.language,
    (newValue) => {
        language.value = newValue;
    },
);
watch(
    () => language.value,
    (newValue) => {
        props.onLanguageChange(newValue);
    },
);
</script>

<template>
    <div
        :id="edit ? 'profile-user-edit' : ''"
        @click="
            () => {
                if (edit) {
                    modalStore.openModal('userInfo');
                }
            }
        "
        class="group container relative flex w-full flex-col justify-between rounded bg-white p-6 text-zinc-600 shadow-md dark:bg-zinc-800 dark:text-zinc-100 md:w-[15em]"
        :class="{
            'cursor-pointer': edit,
        }"
    >
        <LanguageSelector
            class="absolute left-0 top-0 z-20 !justify-start pl-1"
            @click.prevent.stop=""
            :show-name="false"
            :languages="languages"
            v-model="language"
        />
        <h2
            v-if="edit"
            class="absolute right-0 top-0 pr-2 text-xs font-extrabold text-zinc-500 transition-all duration-150 ease-in-out group-hover:text-zinc-600"
        >
            {{ $t('labels.clickToEdit.field') }}
        </h2>
        <div class="relative z-10 flex flex-col items-center gap-2">
            <Avatar
                @click.stop.prevent="() => {}"
                size="2xl"
                class=""
                :edit-mode="edit"
                :edit-button-id="'edit-avatar-button'"
                :edit-click="
                    () => {
                        if (edit) {
                            modalStore.openModal('userAvatar');
                        }
                    }
                "
                :src="userStore.currentUser.avatarPath()"
            />
            <div class="flex flex-col items-center">
                <h2 class="text-center text-lg font-bold uppercase">
                    {{ user.name }}
                </h2>
                <h2 class="text-center font-bold">
                    {{ user.tag }}
                </h2>
            </div>
        </div>
    </div>
</template>
