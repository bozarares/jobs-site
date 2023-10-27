import { faker } from '@faker-js/faker';

describe('Profile edit flow', () => {
    beforeEach(() => {
        cy.visit('https://jobs.test');
        cy.login('test@example.com', 'password');
        cy.visit('https://jobs.test/profile');
        cy.get(`#edit-profile-button`).click();
    });

    it('Edit User Success', () => {
        cy.get(`#profile-user-edit`).click();

        const name = faker.person.fullName();
        const tag = faker.person.jobTitle();

        cy.get('input[name="name"]').clear().type(name);
        cy.get('input[name="tag"]').clear().type(tag);
        cy.contains('button', 'Save').click();
        cy.get('#profile-user-edit')
            .should('contain', name)
            .should('contain', tag);
    });

    it('Edit User Socials', () => {
        cy.get(`#profile-socials-edit`).click();

        const phone = faker.phone.number('+40 7## ### ###');
        const username = faker.internet.userName();

        cy.get('input[name="phone_number"]').clear().type(phone);
        cy.get('input[name="linkedin"]').clear().type(username);
        cy.get('input[name="github"]').clear().type(username);
        cy.get('input[name="facebook"]').clear().type(username);
        cy.contains('button', 'Save').click();

        cy.contains('h2', phone).should('be.visible');
        cy.contains(
            `a[href="https://linkedin.com/in/${username}"]`,
            'LinkedIn',
        ).should('be.visible');
        cy.contains(
            `a[href="https://github.com/${username}"]`,
            'Github',
        ).should('be.visible');
        cy.contains(
            `a[href="https://facebook.com/${username}"]`,
            'Facebook',
        ).should('be.visible');
    });

    it('Edit Profile Avatar Success', () => {
        cy.intercept('POST', '/upload/process').as('uploadRequest');

        cy.get(`#edit-avatar-button`).click();

        cy.get('input[type=file]').attachFile('thispersondoesnotexist.jpeg', {
            force: true,
        });
        cy.wait('@uploadRequest').its('response.statusCode').should('eq', 200);
        cy.wait(2000);
        cy.contains('button', 'Save').click();
    });

    it('Edit Description Success', () => {
        cy.get(`#profile-description-edit`).click();

        const description = `<p>Test Description ${faker.lorem.paragraph()}</p>`;

        cy.get('.ql-container > .ql-editor').clear().type(description);
        cy.contains('button', 'Save').click();
        cy.get('#profile-description-edit > .ql-editor').should(
            'contain',
            description,
        );
    });

    it('Edit Jobs Success', () => {
        cy.get(`#profile-jobs-edit`).click();

        cy.intercept('POST', '/profile/update/jobHistory').as('postJobHistory');
        cy.intercept('PUT', '/profile/update/jobHistory').as('putJobHistory');
        cy.intercept('DELETE', '/profile/update/jobHistory').as(
            'deleteJobHistory',
        );

        const companyName = faker.company.name();
        const title = faker.person.jobTitle();
        const description = faker.lorem.words(10);

        cy.get('input[name="company"]').clear().type(companyName);
        cy.get('input[name="title"]').clear().type(title);
        cy.get('input[name="description"]').clear().type(description);
        cy.get('input[name="start_date"]').click();
        cy.get('.grid > :nth-child(9)').click();
        cy.get('input[name="end_date"]').click();
        cy.get('.grid > :nth-child(13)').click();
        cy.contains('button', 'Add Job').click();

        cy.wait('@postJobHistory').its('response.statusCode').should('eq', 302);

        cy.get('#job-history-modal').should('contain', companyName);
        cy.get('#profile-jobs-edit').should('contain', companyName);
        cy.get('#job-history-modal').should('contain', title);
        cy.get('#profile-jobs-edit').should('contain', title);
        cy.get('#job-history-modal').should('contain', description);
        cy.get('#profile-jobs-edit').should('contain', description);

        cy.get('#job-history-modal')
            .contains(companyName)
            .parent()
            .parent()
            .find('button[aria-label="Edit button"]')
            .click();

        const newCompanyName = faker.company.name();
        const newTitle = faker.person.jobTitle();
        const newDescription = faker.lorem.words(10);

        cy.get('input[name="company"]').clear().type(newCompanyName);
        cy.get('input[name="title"]').clear().type(newTitle);
        cy.get('input[name="description"]').clear().type(newDescription);
        cy.get('input[name="start_date"]').click();
        cy.get('.grid > :nth-child(9)').click();
        cy.get('input[type="checkbox"]').click();
        cy.get('input[name="end_date"]').click();
        cy.get('.grid > :nth-child(13)').click();
        cy.contains('button', 'Edit Job').click();

        cy.wait('@putJobHistory').its('response.statusCode').should('eq', 303);

        cy.get('#job-history-modal').should('contain', newCompanyName);
        cy.get('#profile-jobs-edit').should('contain', newCompanyName);
        cy.get('#job-history-modal').should('contain', newTitle);
        cy.get('#profile-jobs-edit').should('contain', newTitle);
        cy.get('#job-history-modal').should('contain', newDescription);
        cy.get('#profile-jobs-edit').should('contain', newDescription);

        cy.get('#job-history-modal')
            .contains(newCompanyName)
            .parent()
            .parent()
            .find('button[aria-label="Delete button"]')
            .click();

        cy.wait('@deleteJobHistory')
            .its('response.statusCode')
            .should('eq', 303);
    });

    it('Edit Education Success', () => {
        cy.get(`#profile-education-edit`).click();

        cy.intercept('POST', '/profile/update/educationHistory').as(
            'postEducationHistory',
        );
        cy.intercept('PUT', '/profile/update/educationHistory').as(
            'putEducationHistory',
        );
        cy.intercept('DELETE', '/profile/update/educationHistory').as(
            'deleteEducationHistory',
        );

        const institutionName = faker.company.name();
        const degree = faker.person.jobTitle();
        const fieldOfStudy = faker.lorem.words(3);

        cy.get('input[name="institution"]').clear().type(institutionName);
        cy.get('input[name="degree"]').clear().type(degree);
        cy.get('input[name="field_of_study"]').clear().type(fieldOfStudy);
        cy.get('input[name="start_date"]').click();
        cy.get('.grid > :nth-child(9)').click();
        cy.get('input[name="end_date"]').click();
        cy.get('.grid > :nth-child(13)').click();
        cy.contains('button', 'Add Education').click();

        cy.wait('@postEducationHistory')
            .its('response.statusCode')
            .should('eq', 302);

        cy.get('#education-history-modal').should('contain', institutionName);
        cy.get('#profile-education-edit').should('contain', institutionName);
        cy.get('#education-history-modal').should('contain', degree);
        cy.get('#profile-education-edit').should('contain', degree);
        cy.get('#education-history-modal').should('contain', fieldOfStudy);
        cy.get('#profile-education-edit').should('contain', fieldOfStudy);

        cy.get('#education-history-modal')
            .contains(institutionName)
            .parent()
            .parent()
            .find('button[aria-label="Edit button"]')
            .click();

        const newInstitutionName = faker.company.name();
        const newDegree = faker.person.jobTitle();
        const newFieldOfStudy = faker.lorem.words(10);

        cy.get('input[name="institution"]').clear().type(newInstitutionName);
        cy.get('input[name="degree"]').clear().type(newDegree);
        cy.get('input[name="field_of_study"]').clear().type(newFieldOfStudy);
        cy.get('input[name="start_date"]').click();
        cy.get('.grid > :nth-child(9)').click();
        cy.get('input[type="checkbox"]').click();
        cy.get('input[name="end_date"]').click();
        cy.get('.grid > :nth-child(13)').click();
        cy.contains('button', 'Edit Education').click();

        cy.wait('@putEducationHistory')
            .its('response.statusCode')
            .should('eq', 303);

        cy.get('#education-history-modal').should(
            'contain',
            newInstitutionName,
        );
        cy.get('#profile-education-edit').should('contain', newInstitutionName);
        cy.get('#education-history-modal').should('contain', newDegree);
        cy.get('#profile-education-edit').should('contain', newDegree);
        cy.get('#education-history-modal').should('contain', newFieldOfStudy);
        cy.get('#profile-education-edit').should('contain', newFieldOfStudy);

        cy.get('#education-history-modal')
            .contains(newInstitutionName)
            .parent()
            .parent()
            .find('button[aria-label="Delete button"]')
            .click();

        cy.wait('@deleteEducationHistory')
            .its('response.statusCode')
            .should('eq', 303);
    });

    it('Edit Skills Success', () => {
        cy.get(`#profile-skills-edit`).click();
        const skill = faker.lorem.words(1);
        cy.get('input[name="add_skill"]').clear().type(skill);
        cy.contains('button', 'Add').click();
        cy.get('#skills-modal').should('contain', skill);
        cy.contains('button', 'Save').click();
        cy.get('#profile-skills-edit').should('contain', skill);
        cy.get(`#profile-skills-edit`).click();
        cy.get('#skills-modal')
            .contains(skill)
            .parent()
            .find('div[aria-label="Delete button"]')
            .click();
        cy.contains('button', 'Save').click();
        cy.get('#profile-skills-edit').should('not.contain', skill);
    });

    it('Edit Files Success', () => {
        cy.intercept('POST', '/upload/process').as('uploadRequest');
        cy.intercept('POST', '/profile/update/files').as('uploadFileRequest');
        cy.intercept('DELETE', '/profile/update/files').as('deleteFileRequest');
        cy.get(`#profile-files-edit`).click();
        cy.get('input[type=file]').attachFile('dummy.pdf', {
            force: true,
        });
        cy.wait('@uploadRequest').its('response.statusCode').should('eq', 200);
        cy.wait(2000);
        const name = faker.lorem.words(1);
        cy.get('input[name="file_name"')
            .should('have.value', 'dummy')
            .clear()
            .type(name);
        cy.contains('button', 'Add').click();

        cy.wait('@uploadFileRequest').then((interception) => {
            expect(interception.response.statusCode).to.eq(200);
            expect(interception.response.body).to.have.property(
                'success',
                true,
            );
        });

        cy.get('input[name="file_name"')
            .should('not.have.value', name)
            .should('have.value', '');
        cy.get('#profile-files-edit')
            .should('not.contain', 'dummy')
            .should('contain', name);
        cy.get('#files-modal')
            .should('not.contain', 'dummy')
            .should('contain', name);
        cy.get('#files-modal')
            .contains(name)
            .parent()
            .find('div[aria-label="Delete button"]')
            .click();
        cy.wait('@deleteFileRequest').then((interception) => {
            expect(interception.response.statusCode).to.eq(200);
            expect(interception.response.body).to.have.property(
                'success',
                true,
            );
        });
        cy.get('#profile-files-edit').should('not.contain', name);
        cy.get('#files-modal').should('not.contain', name);
    });

    it('Add Files and refresh', () => {
        cy.intercept('POST', '/upload/process').as('uploadRequest');
        cy.intercept('DELETE', '/upload/delete').as('abortRequest');
        cy.get(`#profile-files-edit`).click();
        cy.get('input[type=file]').attachFile('dummy.pdf', {
            force: true,
        });
        cy.wait('@uploadRequest').its('response.statusCode').should('eq', 200);
        cy.wait(2000);

        cy.get('input[name="file_name"').should('have.value', 'dummy');

        cy.reload();
        cy.wait('@abortRequest').then((interception) => {
            expect(interception.response.statusCode).to.eq(200);
            expect(interception.response.body).to.have.property(
                'success',
                true,
            );
        });
    });

    it('Add Files and close modal', () => {
        cy.intercept('POST', '/upload/process').as('uploadRequest');
        cy.intercept('DELETE', '/upload/delete').as('abortRequest');
        cy.get(`#profile-files-edit`).click();
        cy.get('input[type=file]').attachFile('dummy.pdf', {
            force: true,
        });
        cy.wait('@uploadRequest').its('response.statusCode').should('eq', 200);
        cy.wait(2000);

        cy.get('input[name="file_name"').should('have.value', 'dummy');

        cy.get('#modal-overlay').click('topLeft', { force: true });
        cy.wait('@abortRequest').then((interception) => {
            expect(interception.response.statusCode).to.eq(200);
            expect(interception.response.body).to.have.property(
                'success',
                true,
            );
        });
    });

    it('Add Avatar and refresh', () => {
        cy.intercept('POST', '/upload/process').as('uploadRequest');
        cy.intercept('DELETE', '/upload/delete').as('abortRequest');
        cy.get(`#edit-avatar-button`).click();
        cy.get('input[type=file]').attachFile('thispersondoesnotexist.jpeg', {
            force: true,
        });
        cy.wait('@uploadRequest').its('response.statusCode').should('eq', 200);
        cy.wait(2000);

        cy.reload();
        cy.wait('@abortRequest').then((interception) => {
            expect(interception.response.statusCode).to.eq(200);
            expect(interception.response.body).to.have.property(
                'success',
                true,
            );
        });
    });

    it('Add Avatar and close modal', () => {
        cy.intercept('POST', '/upload/process').as('uploadRequest');
        cy.intercept('DELETE', '/upload/delete').as('abortRequest');
        cy.get(`#edit-avatar-button`).click();

        cy.get('input[type=file]').attachFile('thispersondoesnotexist.jpeg', {
            force: true,
        });
        cy.wait('@uploadRequest').its('response.statusCode').should('eq', 200);
        cy.wait(2000);

        cy.get('#modal-overlay').click('topLeft', { force: true });
        cy.wait('@abortRequest').then((interception) => {
            expect(interception.response.statusCode).to.eq(200);
            expect(interception.response.body).to.have.property(
                'success',
                true,
            );
        });
    });
});
