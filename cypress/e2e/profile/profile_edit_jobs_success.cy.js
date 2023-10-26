import { faker } from '@faker-js/faker';

describe('template spec', () => {
    it('Edit Profile Jobs Success', () => {
        cy.visit('https://jobs.test');
        cy.login('test@example.com', 'password');
        cy.visit('https://jobs.test/profile');
        cy.get(`#edit-profile-button`).click();
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
});
