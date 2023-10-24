<script setup>
import UpdateAvatar from './Modals/UpdateAvatar.vue';
import UpdateDescription from './Modals/UpdateDescription.vue';
import UpdateEducationHistory from './Modals/UpdateEducationHistory.vue';
import UpdateJobHistory from './Modals/UpdateJobHistory.vue';
import UpdateSkills from './Modals/UpdateSkills.vue';
import UpdateSocials from './Modals/UpdateSocials.vue';
import UpdateUser from './Modals/UpdateUser.vue';
const props = defineProps({
    modal: { type: String, default: null },
    closeModal: { type: Function, default: () => {} },
});

import { watchEffect } from 'vue';

watchEffect(() => {
    if (props.modal) {
        document.body.style.overflow = 'hidden';
    } else {
        document.body.style.overflow = '';
    }
});
</script>

<template>
    <UpdateAvatar
        v-if="modal === 'avatar'"
        :close-modal="
            () => {
                modal = null;
                closeModal();
            }
        "
    />
    <UpdateUser
        v-if="modal === 'user'"
        :close-modal="
            () => {
                modal = null;
                closeModal();
            }
        "
    />
    <UpdateDescription
        v-if="modal === 'description'"
        :close-modal="
            () => {
                modal = null;
                closeModal();
            }
        "
    />
    <UpdateSocials
        v-if="modal === 'socials'"
        :close-modal="
            () => {
                modal = null;
                closeModal();
            }
        "
    />
    <UpdateJobHistory
        v-if="modal === 'jobs'"
        :close-modal="
            () => {
                modal = null;
                closeModal();
            }
        "
    />
    <UpdateEducationHistory
        v-if="modal === 'education'"
        :close-modal="
            () => {
                modal = null;
                closeModal();
            }
        "
    />
    <UpdateSkills
        v-if="modal === 'skills'"
        :close-modal="
            () => {
                modal = null;
                closeModal();
            }
        "
    />
</template>
