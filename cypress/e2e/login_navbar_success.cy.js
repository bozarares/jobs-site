describe('template spec', () => {
    it('Test Login success with navbar and logout', () => {
        cy.visit('https://jobs.test');
        cy.get('#user-toggle').click();
        cy.get('#navbar-login').should('contain.text', 'Login');

        cy.get('input[type="email"]').type('test@example.com');
        cy.get('input[type="password"]').type('password');
        cy.contains('button', 'Login').click();

        cy.get('#navbar-user');
        cy.contains('button', 'Logout').click();
        cy.get('#navbar-login').should('contain.text', 'Login');
    });
});
