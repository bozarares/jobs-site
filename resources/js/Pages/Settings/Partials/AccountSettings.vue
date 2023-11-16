<script setup>
import { Button, Input } from '@/Components/UI';
import { useCurrentUser } from '@/Composables/useCurrentUser';
import { Link, router } from '@inertiajs/vue3';

const currentUser = useCurrentUser();

const email = ref('');

const editEmail = async () => {
    const response = await axios.post(route('edit.email'), {
        email: email.value,
    });
    router.reload({ preserveState: true });
};
</script>

<template>
    <h2 class="text-2xl font-extrabold">
        {{ $t('sections.settings.account') }}
    </h2>
    <div class="flex flex-col justify-center gap-4">
        <div class="mt-4 flex flex-col items-start justify-center gap-2">
            <h2 class="w-full text-xl font-bold">
                {{ $t('labels.email.change') }}
            </h2>
            <h3 class="w-full text-sm font-bold">
                {{ currentUser.email }}
                <span
                    class="text-green-400"
                    v-if="currentUser.email_verified_at"
                    >{{ $t('labels.email.verified') }}</span
                >
                <Link
                    v-else
                    :href="route('verification.send')"
                    method="POST"
                    as="button"
                    class="text-amber-400"
                    >{{ $t('labels.email.verify') }}</Link
                >
            </h3>
            <Input :label="$t('labels.email.new')" v-model="email" />
            <Button
                class="w-full"
                @click="
                    () => {
                        editEmail();
                    }
                "
                >{{ $t('labels.email.change') }}</Button
            >
        </div>
        <div class="mt-4 flex flex-col items-center justify-center gap-2">
            <h2 class="w-full text-xl font-bold">Authentication Methods</h2>
            <h3 class="w-full text-sm">
                You must have at least one authentication method
            </h3>
            <div class="flex w-full items-center justify-between gap-4">
                <h3>Email & Password</h3>
                <Button>Linked</Button>
            </div>
            <div class="flex w-full items-center justify-between gap-4">
                <h3>Google</h3>
                <Button>Link</Button>
            </div>
        </div>
        <div class="mt-4 flex flex-col items-center justify-center gap-2">
            <h2 class="w-full text-xl font-bold">
                {{ $t('labels.account.delete') }}
            </h2>

            <Button class="w-[15em]" :options="{ color: 'red' }">{{
                $t('labels.account.delete')
            }}</Button>
        </div>
    </div>
</template>
