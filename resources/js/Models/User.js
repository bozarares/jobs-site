export default class User {
    constructor(attibutes = {}) {
        Object.assign(this, attibutes);
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
