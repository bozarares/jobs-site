export default class Company {
    constructor(attibutes = {}) {
        Object.assign(this, attibutes);
    }

    logoPath() {
        return this && this.logo && this.logo_extension
            ? `/logos/companies/${this.logo}.${this.logo_extension}`
            : null;
    }

    isSet() {
        return !!this.title;
    }
}
