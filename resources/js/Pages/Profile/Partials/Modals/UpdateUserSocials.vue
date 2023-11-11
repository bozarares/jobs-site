<script setup>
// TODO: Change form with axios
// TODO: Change controller so it will send a response

import { useForm, usePage } from '@inertiajs/vue3';
import { Button, Input } from '@/Components/UI';
import { PhoneIcon, XMarkIcon } from '@heroicons/vue/24/outline';
import LinkedinIcon from '@/Components/UI/Icons/LinkedinIcon.vue';
import FacebookIcon from '@/Components/UI/Icons/FacebookIcon.vue';
import GithubIcon from '@/Components/UI/Icons/GithubIcon.vue';

const props = defineProps({
    closeModal: { type: Function, default: () => {} },
});

const page = usePage();
const user = page.props.auth.user;

const form = useForm({
    phone_number: user.phone_number,
    social_linkedin: user.social_linkedin,
    social_github: user.social_github,
    social_facebook: user.social_facebook,
});

const submit = () => {
    form.post(route('profile.update.socials'), {
        onFinish: () => {
            props.closeModal();
        },
    });
};
</script>

<template>
    <div
        class="container relative mx-auto flex max-h-[35em] max-w-lg flex-col gap-8 overflow-auto rounded bg-white p-8 shadow dark:bg-zinc-800"
    >
        <div class="flex items-center justify-between">
            <h2
                class="text-lg font-bold uppercase text-zinc-500 dark:text-zinc-300"
            >
                {{ $t('labels.social.edit') }}
            </h2>
            <XMarkIcon
                class="h-6 cursor-pointer text-zinc-500 dark:text-zinc-300"
                @click="closeModal()"
            />
        </div>

        <div class="flex flex-col gap-4 overflow-auto">
            <Input
                v-model="form.phone_number"
                :error="form.errors.phone_number"
                :label="$t('labels.phone.self')"
                type="text"
                name="phone_number"
                :options="{ leftIcon: PhoneIcon, size: 'small' }"
            />
            <div class="flex items-center gap-1">
                <p class="text-xl font-bold text-zinc-800 dark:text-zinc-100">
                    linkedin.com/in/
                </p>
                <Input
                    v-model="form.social_linkedin"
                    :error="form.errors.social_linkedin"
                    label="linkedin"
                    type="text"
                    name="linkedin"
                    :options="{ size: 'small', leftIcon: LinkedinIcon }"
                />
            </div>
            <div class="flex items-center gap-1">
                <p class="text-xl font-bold text-zinc-800 dark:text-zinc-100">
                    github.com/
                </p>
                <Input
                    v-model="form.social_github"
                    :error="form.errors.social_github"
                    label="github"
                    type="text"
                    name="github"
                    :options="{ size: 'small', leftIcon: GithubIcon }"
                />
            </div>
            <div class="flex items-center gap-1">
                <p class="text-xl font-bold text-zinc-800 dark:text-zinc-100">
                    facebook.com/
                </p>
                <Input
                    v-model="form.social_facebook"
                    :error="form.errors.social_facebook"
                    label="facebook"
                    type="text"
                    name="facebook"
                    :options="{ size: 'small', leftIcon: FacebookIcon }"
                />
            </div>
        </div>
        <Button
            @click="submit"
            class=""
            :options="{ color: 'blue', shape: 'pill' }"
            >{{ $t('common.save') }}</Button
        >
    </div>
</template>
