describe('template spec', () => {
    it('Test Login Fails with connect', () => {
        cy.visit('https://jobs.test/connect');
        cy.get('#connect-register');

        cy.contains('button', 'Login').click();

        cy.contains('div', 'The email field is required.').should('be.visible');
        cy.contains('div', 'The password field is required.').should(
            'be.visible',
        );

        const randomEmail = `test${Math.floor(
            Math.random() * 10000,
        )}@example.com`;
        const randomPassword = `Password${Math.floor(Math.random() * 10000)}`;

        cy.get('#connect-login input[name="email"]').type(randomEmail);

        cy.get('#connect-login input[name="password"]').type(randomPassword);

        cy.contains('button', 'Login').click();

        cy.contains(
            'div',
            'These credentials do not match our records.',
        ).should('be.visible');
    });
});
