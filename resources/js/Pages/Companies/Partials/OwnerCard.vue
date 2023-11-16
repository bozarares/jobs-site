<script setup>
import { useModalStore } from '@/Stores/modalStore';
import heroiconsSparkles from '~icons/heroicons/sparkles-solid';

const modalStore = useModalStore();

const props = defineProps({
    class: {
        type: String,
        default: '',
    },
    toggleEdit: {
        type: Function,
        default: () => {},
    },
    applications: {
        type: Number,
        default: 0,
    },
});

const edit = ref(false);
</script>

<template>
    <div
        :class="class"
        class="group container relative flex w-full flex-col items-center justify-between overflow-hidden rounded bg-white p-6 shadow-md dark:bg-zinc-800 dark:text-zinc-100 md:w-[15em]"
    >
        <h2 class="mb-2 text-center text-lg font-bold uppercase">
            {{ $t('labels.owner.self') }}
        </h2>
        <div class="mt-3 flex w-[10em] flex-col items-center gap-3 md:w-full">
            <div class="flex items-center gap-2">
                <h2 class="text-sm">{{ $t('actions.editCompany') }}</h2>
                <Switch
                    :on-change="
                        (value) => {
                            edit = value;
                            toggleEdit(edit);
                        }
                    "
                />
            </div>
            <Button
                class="w-full"
                :options="{
                    shape: 'pill',
                    color: 'gold',
                    leftIcon: heroiconsSparkles,
                }"
                >{{ $t('common.feature') }}</Button
            >
            <!-- <Button
                class="w-full"
                :options="{
                    shape: 'pill',
                    color: 'blue',
                }"
                @click="modalStore.openModal('companyCreateJob')"
                >{{ $t('actions.createJob') }}</Button
            > -->
            <!-- <Button
                class="w-full"
                :options="{
                    shape: 'pill',
                    color: 'blue',
                }"
                >{{ $tc('labels.applications', props.applications) }}</Button
            > -->
        </div>
    </div>
</template>
