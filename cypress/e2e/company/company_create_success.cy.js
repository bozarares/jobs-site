import { faker } from '@faker-js/faker';

describe('template spec', () => {
    it('Test Company Create Success', () => {
        cy.visit('https://jobs.test');
        cy.login('test@example.com', 'password');
        cy.intercept('POST', '/upload/process').as('uploadRequest');

        cy.visit('https://jobs.test/try-recruiting');
        // cy.contains('a', 'Try recruiting').click();
        const companyName = faker.person.firstName();
        const companyCode = `RO${Math.floor(Math.random() * 10000)}`;

        const address = 'srt. Ion Rațiu nr. 1';
        const phone = faker.phone.number();
        const email = faker.internet.email();
        const country = 'Romania';
        const state = 'Constanța County';
        const town = 'Constanţa';
        const description = `<p>Test Description ${faker.lorem.paragraph()}</p>`;

        cy.get('input[name="Company name"]').type(companyName);
        cy.get('input[name="Company code"]').type(companyCode);
        cy.get('input[name="Company address"]').type(address);
        cy.get('input[name="Contact email"]').type(email);
        cy.get('input[name="Phone number"]').type(phone);
        cy.get('div .ql-editor').type(description);

        cy.get('input[name="Company country"]').type(country);
        cy.get('input[name="Company state"]').type(state);
        cy.wait(3000);
        cy.get('input[name="Company town"]', { wait: 1000 }).type(town);

        cy.get('input[type=file]').attachFile('logo-black.png', {
            force: true,
        });
        cy.wait('@uploadRequest').its('response.statusCode').should('eq', 200);
        cy.wait(2000);
        cy.contains('button', 'Create').click();
    });
});
