<script setup>
import UpdateAvatar from './Modals/UpdateAvatar.vue';
import UpdateDescription from './Modals/UpdateDescription.vue';
import UpdateEducationHistory from './Modals/UpdateEducationHistory.vue';
import UpdateFiles from './Modals/UpdateFiles.vue';
import UpdateJobHistory from './Modals/UpdateJobHistory.vue';
import UpdateSkills from './Modals/UpdateSkills.vue';
import UpdateSocials from './Modals/UpdateSocials.vue';
import UpdateUser from './Modals/UpdateUser.vue';
const props = defineProps({
    modal: { type: String, default: null },
    closeModal: { type: Function, default: () => {} },
});
function disableScroll() {
    // Get the current page scroll position
    const scrollTop = window.scrollY || document.documentElement.scrollTop;
    const scrollLeft = window.scrollX || document.documentElement.scrollLeft;
    window.onscroll = function () {
        window.scrollTo(scrollLeft, scrollTop);
    };
}

function enableScroll() {
    window.onscroll = function () {};
}
import { watchEffect } from 'vue';

watchEffect(() => {
    if (props.modal) {
        disableScroll();
    } else {
        enableScroll();
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
                    @click="closeModal()"
                    class="absolute inset-0 bg-black opacity-50"
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
