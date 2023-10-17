describe('template spec', () => {
    it('Test Register Success with connect', () => {
        cy.visit('https://jobs.test/connect');
        cy.get('#connect-register');
        cy.contains('div', 'I agree with terms and conditions')
            .should('be.visible')
            .click();
        const randomName = `Test${Math.floor(Math.random() * 10000)}`;
        const randomEmail = `test${Math.floor(
            Math.random() * 10000,
        )}@example.com`;
        const randomPassword = `Password${Math.floor(Math.random() * 10000)}`;
        cy.get('#connect-register input[name="name"]').type(randomName);
        cy.get('#connect-register input[name="email"]').type(randomEmail);
        cy.get('#connect-register input[name="password"]').type(randomPassword);
        cy.get('#connect-register input[name="password_confirmation"]').type(
            randomPassword,
        );

        cy.contains('button', 'Register').click();
        cy.get('#user-toggle').click();
        cy.get('#navbar-user');
        cy.contains('button', 'Logout').click();
        cy.get('#navbar-login').should('contain.text', 'Login');
    });
});
