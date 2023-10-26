import { faker } from '@faker-js/faker';

describe('template spec', () => {
    it('Edit Profile Avatar Success', () => {
        cy.visit('https://jobs.test');
        cy.login('test@example.com', 'password');
        cy.visit('https://jobs.test/profile');
        cy.get(`#edit-profile-button`).click();
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
});
