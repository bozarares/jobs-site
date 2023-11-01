<script setup>
import { ref } from 'vue';
import { onBeforeMount } from 'vue';
import { useModalStore } from '@/Stores/modalStore';
import { watchEffect } from 'vue';
import { defineAsyncComponent } from 'vue';

const modalStore = useModalStore();

const isClient = ref(false);
onBeforeMount(() => {
    isClient.value = typeof window !== 'undefined';
});

function disableScroll() {
    if (isClient.value) {
        document.body.addEventListener('mousewheel', preventScroll, {
            passive: false,
        });
        document.body.addEventListener('touchmove', preventScroll, {
            passive: false,
        });
    }
}
function enableScroll() {
    if (isClient.value) {
        document.body.removeEventListener('mousewheel', preventScroll, {
            passive: false,
        });
        document.body.removeEventListener('touchmove', preventScroll, {
            passive: false,
        });
    }
}

function preventScroll(e) {
    e.preventDefault();
}

watchEffect(() => {
    if (isClient.value) {
        if (modalStore.modal) {
            disableScroll();
            document.body.style.inlineSize = '100%';
            document.body.style.overflowY = 'scroll';
        } else {
            enableScroll();
            document.body.style.inlineSize = 'auto';
            document.body.style.overflowY = 'auto';
        }
    }
});

const componentMap = {
    // Profile Modals
    userAvatar: defineAsyncComponent(() =>
        import('@/Pages/Profile/Partials/Modals/UpdateUserAvatar.vue'),
    ),
    userInfo: defineAsyncComponent(() =>
        import('@/Pages/Profile/Partials/Modals/UpdateUserInfo.vue'),
    ),
    userDescription: defineAsyncComponent(() =>
        import('@/Pages/Profile/Partials/Modals/UpdateUserDescription.vue'),
    ),
    userSocials: defineAsyncComponent(() =>
        import('@/Pages/Profile/Partials/Modals/UpdateUserSocials.vue'),
    ),
    userJobs: defineAsyncComponent(() =>
        import('@/Pages/Profile/Partials/Modals/UpdateUserJobHistory.vue'),
    ),
    userEducation: defineAsyncComponent(() =>
        import(
            '@/Pages/Profile/Partials/Modals/UpdateUserEducationHistory.vue'
        ),
    ),
    userSkills: defineAsyncComponent(() =>
        import('@/Pages/Profile/Partials/Modals/UpdateUserSkills.vue'),
    ),
    userFiles: defineAsyncComponent(() =>
        import('@/Pages/Profile/Partials/Modals/UpdateUserFiles.vue'),
    ),

    // Company Modals
    companyLogo: defineAsyncComponent(() =>
        import('@/Pages/Companies/Partials/Modals/UpdateCompanyLogo.vue'),
    ),
    companyDescription: defineAsyncComponent(() =>
        import(
            '@/Pages/Companies/Partials/Modals/UpdateCompanyDescription.vue'
        ),
    ),
    companyContact: defineAsyncComponent(() =>
        import('@/Pages/Companies/Partials/Modals/UpdateCompanyContact.vue'),
    ),
    companyCreateJob: defineAsyncComponent(() =>
        import('@/Pages/Companies/Partials/Modals/CreateCompanyJob.vue'),
    ),

    // Job Modals
    jobApply: defineAsyncComponent(() =>
        import('@/Pages/Jobs/Partials/Modals/ApplyToJob.vue'),
    ),
    jobSkills: defineAsyncComponent(() =>
        import('@/Pages/Jobs/Partials/Modals/UpdateJobSkills.vue'),
    ),
    jobDelete: defineAsyncComponent(() =>
        import('@/Pages/Jobs/Partials/Modals/DeleteJob.vue'),
    ),
    jobDescription: defineAsyncComponent(() =>
        import('@/Pages/Jobs/Partials/Modals/UpdateJobDescription.vue'),
    ),
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
                v-if="modalStore.modal"
                class="fixed inset-0 z-50 flex flex-col items-center justify-center gap-4"
            >
                <div
                    id="modal-overlay"
                    @click="modalStore.closeModal()"
                    class="absolute inset-0 bg-black opacity-20"
                ></div>
                <component
                    :is="componentMap[modalStore.modal]"
                    :close-modal="
                        () => {
                            modalStore.closeModal();
                        }
                    "
                />
            </div>
        </Transition>
    </Teleport>
</template>

<style></style>
