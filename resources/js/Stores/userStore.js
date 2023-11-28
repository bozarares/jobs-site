// userStore.js

import User from '@/Models/User';
import { broadcastDisconnect, broadcastListen } from '@/broadcast';
import { defineStore } from 'pinia';

export const useUserStore = defineStore('user', {
    state: () => ({
        currentUser: new User(),
    }),
    actions: {
        async initializeUser(userData) {
            if (this.currentUser.isSet()) return;
            this.currentUser = new User(userData);
            broadcastListen(this.currentUser.id);
            if (this.currentUser.isSet()) {
                await this.currentUser.initializeNotifications();
            }
        },
        clearUser() {
            broadcastDisconnect();
            this.currentUser = delete this.currentUser;
            this.currentUser = new User();
        },
    },
});
