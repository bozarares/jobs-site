<script setup>
import { useForm, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
import { Button } from '@/Components/UI';
import Input from '@/Components/UI/Input/Input.vue';
import { PhoneIcon, UserIcon } from '@heroicons/vue/24/outline';
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
                    Edit Socials
                </h2>

                <div class="overflow-auto flex flex-col gap-4">
                    <Input
                        v-model="form.phone_number"
                        :error="form.errors.phone_number"
                        label="Phone number"
                        type="text"
                        name="phone_number"
                        :options="{ leftIcon: PhoneIcon, size: 'small' }"
                    />
                    <div class="flex items-center gap-1">
                        <p class="text-xl font-bold text-black/60">
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
                        <p class="text-xl font-bold text-black/60">
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
                        <p class="text-xl font-bold text-black/60">
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
                    :options="{ color: 'green', shape: 'pill' }"
                    >Save</Button
                >
            </div>
        </div>
    </Teleport>
</template>
