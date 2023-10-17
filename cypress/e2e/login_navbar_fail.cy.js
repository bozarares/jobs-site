describe('template spec', () => {
    it('Test Login Fail with navbar', () => {
        cy.visit('https://jobs.test');
        cy.get('#user-toggle').click();
        cy.get('#navbar-login').should('contain.text', 'Login');
        cy.contains('button', 'Login').click();
        cy.contains('div', 'The email field is required.').should('be.visible');
        cy.contains('div', 'The password field is required.').should(
            'be.visible',
        );

        // Input random values into email and password fields
        const randomEmail = `test${Math.floor(
            Math.random() * 10000,
        )}@example.com`;
        const randomPassword = `Password${Math.floor(Math.random() * 10000)}`;

        cy.get('input[type="email"]').type(randomEmail);
        cy.get('input[type="password"]').type(randomPassword);

        // Click the login button again
        cy.contains('button', 'Login').click();
        cy.contains(
            'div',
            'These credentials do not match our records.',
        ).should('be.visible');
    });
});
