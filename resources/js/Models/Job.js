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
        return this.application && this.application.application_date
            ? dayjs(this.application.application_date).format('D MMM. YYYY')
            : null;
    }

    get formattedSeenDate() {
        return this.application && this.application.seen_at
            ? dayjs(this.application.seen_at).format('D MMM. YYYY')
            : null;
    }

    get experiences() {
        return this.levels?.join(', ');
    }

    get getApplication() {
        return this.application
            ? this.application
            : {
                  application_date: null,
                  seen_at: null,
                  status: null,
              };
    }
}
