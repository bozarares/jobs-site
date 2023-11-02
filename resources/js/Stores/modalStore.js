import { defineStore } from 'pinia';

export const useModalStore = defineStore('modalStore', {
    state: () => ({
        modal: null,
        args: null,
    }),
    actions: {
        openModal(name, args = null) {
            this.modal = name;
            this.args = args;
        },
        closeModal() {
            this.modal = null;
            this.args = null;
        },
    },
});
