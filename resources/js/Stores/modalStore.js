import { defineStore } from 'pinia';

export const useModalStore = defineStore('modalStore', {
    state: () => ({
        modal: null,
    }),
    actions: {
        openModal(name) {
            this.modal = name;
        },
        closeModal() {
            this.modal = null;
        },
    },
});
