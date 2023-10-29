<script setup>
import UpdateDescription from '@/Pages/Companies/Partials/Modals/UpdateDescription.vue';
import UpdateContact from '@/Pages/Companies/Partials/Modals/UpdateContact.vue';
import UpdateLogo from '@/Pages/Companies/Partials/Modals/UpdateLogo.vue';
import { onBeforeMount, watchEffect } from 'vue';
import { ref } from 'vue';
import AddJob from './Modals/AddJob.vue';

const props = defineProps({
    modal: { type: String, default: null },
    closeModal: { type: Function, default: () => {} },
});

const isClient = ref(false);
onBeforeMount(() => {
    isClient.value = typeof window !== 'undefined';
});

watchEffect(() => {
    if (isClient.value) {
        if (props.modal) {
            document.body.style.overflow = 'hidden';
        } else {
            document.body.style.overflow = 'auto';
        }
    }
});

const componentMap = {
    logo: UpdateLogo,
    description: UpdateDescription,
    contact: UpdateContact,
    add_job: AddJob,
};

const enterActiveClass = 'transition-all duration-150 ease-out';
const leaveActiveClass = 'transition-all duration-150 ease-out';
const enterFromClass = 'opacity-0';
const enterToClass = 'opacity-100 scale-100';
const leaveFromClass = 'opacity-100 scale-100';
const leaveToClass = 'opacity-0';
</script>

<template>
    <Teleport to="body">
        <Transition
            :enter-active-class="enterActiveClass"
            :leave-active-class="leaveActiveClass"
            :enter-to-class="enterToClass"
            :leave-to-class="leaveToClass"
            :enter-from-class="enterFromClass"
            :leave-from-class="leaveFromClass"
        >
            <div
                v-if="modal"
                class="fixed inset-0 z-50 flex flex-col items-center justify-center gap-4"
            >
                <div
                    @click="closeModal()"
                    class="absolute inset-0 bg-black opacity-20"
                ></div>
                <component
                    :is="componentMap[modal]"
                    :close-modal="
                        () => {
                            modal = null;
                            closeModal();
                        }
                    "
                />
            </div>
        </Transition>
    </Teleport>
</template>

<style></style>
