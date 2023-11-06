<script setup>
import { Switch } from '@/Components/UI';
import Button from '@/Components/UI/Button/Button.vue';
import { useModalStore } from '@/Stores/modalStore';
import { SparklesIcon } from '@heroicons/vue/24/outline';
import { ref } from 'vue';

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
        class="group container relative flex w-full flex-col items-center justify-between overflow-hidden rounded bg-white p-6 shadow-md md:w-[15em]"
    >
        <h2 class="mb-2 text-center text-lg font-bold uppercase">
            {{ $t('fields.owner_panel') }}
        </h2>
        <div class="mt-3 flex w-[10em] flex-col items-center gap-3 md:w-full">
            <div class="flex items-center gap-2">
                <h2 class="text-sm">{{ $t('generic.edit_company') }}</h2>
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
                    leftIcon: SparklesIcon,
                }"
                >{{ $t('buttons.feature') }}</Button
            >

            <Button
                class="w-full"
                :options="{
                    shape: 'pill',
                    color: 'green',
                }"
                @click="modalStore.openModal('companyCreateJob')"
                >{{ $t('buttons.create_job') }}</Button
            >
            <Button
                class="w-full"
                :options="{
                    shape: 'pill',
                    color: 'green',
                }"
                >{{ $tc('buttons.applications', props.applications) }}</Button
            >
        </div>
    </div>
</template>
