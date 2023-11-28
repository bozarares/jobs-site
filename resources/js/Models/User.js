export default class User {
    constructor(attibutes = {}) {
        Object.assign(this, attibutes);
        this.notifications = { page: 1, data: [], lastPage: 1 };
    }

    async initializeNotifications(page = this.page) {
        const response = await axios.post(route('api.notifications'), {
            page: page,
        });
        this.notifications.lastPage = response.data.last_page;
        this.notifications.page = response.data.current_page;
        this.notifications.data = response.data.notifications;
    }

    async getNotifications() {
        const response = await axios.post(route('api.notifications'));
        return response.data;
    }

    avatarPath() {
        return this && this.avatar && this.avatar_extension
            ? `/users/avatars/${this.avatar}.${this.avatar_extension}`
            : null;
    }

    isOwner() {
        if (this) return this.isOwner;
        return false;
    }

    isSet() {
        return !!this.name;
    }
}
