<script setup>
import { Avatar, LanguageSelector } from '@/Components/UI';
import { languages } from '@/Languages/languages';
import { useModalStore } from '@/Stores/modalStore';
import { watch } from 'vue';
import { ref } from 'vue';

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
    onLanguageChange: {
        type: Function,
        default: () => {},
    },
});

const language = ref('en');
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
        class="group container relative flex w-full flex-col justify-between rounded bg-white p-6 shadow-md md:w-[15em]"
        :class="{
            'cursor-pointer': edit,
        }"
    >
        <LanguageSelector
            class="absolute right-0 top-0 !justify-end"
            :show-name="false"
            :languages="languages"
            v-model="language"
        />
        <h2
            v-if="edit"
            class="absolute right-0 top-0 pr-2 text-xs font-extrabold text-gray-500 transition-all duration-150 ease-in-out group-hover:text-black"
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
                :src="
                    $page.props.auth.user.avatar
                        ? '/users/avatars/' +
                          $page.props.auth.user.avatar +
                          '.' +
                          $page.props.auth.user.avatar_extension
                        : null
                "
            />
            <div class="flex flex-col items-center">
                <h2 class="text-center text-lg font-bold uppercase">
                    {{ user.name }}
                </h2>
                <h2 class="text-center font-bold text-black/60">
                    {{ user.tag }}
                </h2>
            </div>
        </div>
    </div>
</template>
