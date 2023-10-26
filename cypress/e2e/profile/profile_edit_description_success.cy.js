import { faker } from '@faker-js/faker';

describe('template spec', () => {
    it('Edit Profile Avatar Success', () => {
        cy.visit('https://jobs.test');
        cy.login('test@example.com', 'password');
        cy.visit('https://jobs.test/profile');
        cy.get(`#edit-profile-button`).click();
        cy.get(`#profile-description-edit`).click();

        const description = `<p>Test Description ${faker.lorem.paragraph()}</p>`;

        cy.get('.ql-container > .ql-editor').clear().type(description);
        cy.contains('button', 'Save').click();
        cy.get('#profile-description-edit > .ql-editor').should(
            'contain',
            description,
        );
    });
});
