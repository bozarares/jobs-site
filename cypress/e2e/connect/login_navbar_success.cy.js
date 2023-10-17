describe('template spec', () => {
    it('Test Login success with navbar and logout', () => {
        cy.visit('https://jobs.test');
        cy.get('#user-toggle').click();
        cy.get('#navbar-login').should('contain.text', 'Login');

        cy.fixture('userData').then((user) => {
            cy.get('input[type="email"]').type(user.email);
            cy.get('input[type="password"]').type(user.password);
        });
        cy.get('#user-toggle').click();

        cy.contains('button', 'Login').click();
        cy.logout(true);
    });
});
