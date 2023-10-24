import { faker } from '@faker-js/faker';

describe('template spec', () => {
    it('Test Company Create Success', () => {
        cy.visit('https://jobs.test');
        cy.login('test@example.com', 'password');
        cy.intercept('POST', '/upload/process').as('uploadRequest');
        cy.intercept('GET', '/try-recruiting').as('createSuccess');
        cy.intercept(
            'GET',
            'https://countriesnow.space/api/v0.1/countries/states/*',
        ).as('getStates');
        cy.intercept(
            'GET',
            'https://countriesnow.space/api/v0.1/countries/state/cities/*',
        ).as('getTowns');

        cy.visit('https://jobs.test/try-recruiting');

        // Create Company
        const companyName = faker.person.firstName();
        const companyCode = `RO${Math.floor(Math.random() * 10000)}`;

        const phone = faker.phone.number();
        const newPhone = faker.phone.number();

        const email = faker.internet.email();
        const newEmail = faker.internet.email();

        const country = 'Romania';
        const newCountry = 'Romania';

        const state = 'Constanța County';
        const newState = 'Bucharest';

        const town = 'Constanţa';
        const newTown = 'Bucharest';

        const address = 'srt. Ion Rațiu nr. 1';
        const newAddress = 'srt. Pipera nr. 1';

        const description = `<p>Test Description ${faker.lorem.paragraph()}</p>`;
        const editDescription = `<p>Test Description ${faker.lorem.paragraph()}</p>`;

        // Attach logo
        cy.get('input[type=file]').attachFile('logo-black.png', {
            force: true,
        });
        cy.wait('@uploadRequest').its('response.statusCode').should('eq', 200);
        cy.get('input[name="Company name"]').type(companyName);
        cy.get('input[name="Company code"]').type(companyCode);
        cy.get('input[name="Contact email"]').type(email);
        cy.get('input[name="Phone number"]').type(phone);
        cy.get('div .ql-editor').type(description);

        cy.get('input[name="Company country"]').type(country);
        cy.wait('@getStates').its('response.statusCode').should('eq', 200);

        cy.get('input[name="Company state"]').type(state);
        cy.wait('@getTowns').its('response.statusCode').should('eq', 200);

        cy.get('input[name="Company town"]', { wait: 1000 }).type(town);
        cy.get('input[name="Company address"]').type(address);
        cy.contains('button', 'Create').click();

        cy.wait('@createSuccess').its('response.statusCode').should('eq', 200);
        cy.wait(500);

        cy.visit('https://jobs.test/my-companies');
        cy.contains('h2', companyName)
            .closest(`#company-card-${companyName}`)
            .find(`#company-view-${companyName}`)
            .click();

        cy.contains('button', 'Edit Company').click();

        cy.get(`#edit-description-button-${companyName}`).click();
        cy.get('div .ql-editor').clear().type(editDescription);
        cy.contains('button', 'Save').click();

        cy.get(`#edit-contact-button-${companyName}`).click();
        cy.get('input[name="Contact email"]').clear().type(newEmail);
        cy.get('input[name="Phone number"]').clear().type(newPhone);

        cy.get('input[name="Company country"]').type(newCountry);
        cy.wait('@getStates').its('response.statusCode').should('eq', 200);

        cy.get('input[name="Company state"]').type(newState);
        cy.wait('@getTowns').its('response.statusCode').should('eq', 200);

        cy.get('input[name="Company town"]', { wait: 1000 }).type(newTown);
        cy.get('input[name="Company address"]').type(newAddress);
        cy.contains('button', 'Save').click();

        cy.get(`#edit-logo-button-${companyName}`).click();
        cy.get('input[type=file]').attachFile('logo-black.png', {
            force: true,
        });
        cy.wait('@uploadRequest').its('response.statusCode').should('eq', 200);
        cy.wait(3000);
        cy.contains('button', 'Save').click();
    });
});
