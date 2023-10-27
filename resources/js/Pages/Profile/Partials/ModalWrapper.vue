<script setup>
import UpdateAvatar from './Modals/UpdateAvatar.vue';
import UpdateDescription from './Modals/UpdateDescription.vue';
import UpdateEducationHistory from './Modals/UpdateEducationHistory.vue';
import UpdateFiles from './Modals/UpdateFiles.vue';
import UpdateJobHistory from './Modals/UpdateJobHistory.vue';
import UpdateSkills from './Modals/UpdateSkills.vue';
import UpdateSocials from './Modals/UpdateSocials.vue';
import UpdateUser from './Modals/UpdateUser.vue';
import { watchEffect } from 'vue';

const props = defineProps({
    modal: { type: String, default: null },
    closeModal: { type: Function, default: () => {} },
});
function disableScroll() {
    document.body.addEventListener('mousewheel', preventScroll, {
        passive: false,
    });
    document.body.addEventListener('touchmove', preventScroll, {
        passive: false,
    });
}
function enableScroll() {
    document.body.removeEventListener('mousewheel', preventScroll, {
        passive: false,
    });
    document.body.removeEventListener('touchmove', preventScroll, {
        passive: false,
    });
}

function preventScroll(e) {
    e.preventDefault();
}

watchEffect(() => {
    if (props.modal) {
        disableScroll();
        document.body.style.inlineSize = '100%';
        document.body.style.overflowY = 'scroll';
    } else {
        enableScroll();
        document.body.style.inlineSize = 'auto';
        document.body.style.overflowY = 'auto';
    }
});

const componentMap = {
    avatar: UpdateAvatar,
    user: UpdateUser,
    description: UpdateDescription,
    socials: UpdateSocials,
    jobs: UpdateJobHistory,
    education: UpdateEducationHistory,
    skills: UpdateSkills,
    files: UpdateFiles,
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
                    id="modal-overlay"
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
