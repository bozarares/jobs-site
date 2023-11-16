import dayjs from 'dayjs';
import Company from './Company';

export default class Job {
    constructor(attributes = {}) {
        Object.assign(this, attributes);

        if (this.company) {
            this.company = new Company(this.company);
        }
    }

    get formattedApplicationDate() {
        return this.application_date
            ? dayjs(this.application_date).format('D MMM. YYYY')
            : null;
    }

    get formattedSeenDate() {
        return this.seen_at ? dayjs(this.seen_at).format('D MMM. YYYY') : null;
    }

    get experiences() {
        return this.levels?.join(', ');
    }
}
