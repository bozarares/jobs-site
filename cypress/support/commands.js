// ***********************************************
// This example commands.js shows you how to
// create various custom commands and overwrite
// existing commands.
//
// For more comprehensive examples of custom
// commands please read more here:
// https://on.cypress.io/custom-commands
// ***********************************************
//
//
// -- This is a parent command --
Cypress.Commands.add('login', (email, password) => {
    cy.visit('https://jobs.test/connect');

    cy.get('#connect-login input[name="email"]').type(email);
    cy.get('#connect-login input[name="password"]').type(password);

    cy.contains('button', 'Login').click();
});

Cypress.Commands.add('logout', () => {
    cy.get('#user-toggle').click();

    // Continuăm cu restul pașilor
    cy.get('#navbar-user', { timeout: 10000 }).should('be.visible');
    cy.contains('button', 'Logout').click();
});

//
//
// -- This is a child command --
// Cypress.Commands.add('drag', { prevSubject: 'element'}, (subject, options) => { ... })
//
//
// -- This is a dual command --
// Cypress.Commands.add('dismiss', { prevSubject: 'optional'}, (subject, options) => { ... })
//
//
// -- This will overwrite an existing command --
// Cypress.Commands.overwrite('visit', (originalFn, url, options) => { ... })
