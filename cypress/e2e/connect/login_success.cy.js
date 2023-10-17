describe('template spec', () => {
    it('Test Login Success with connect', () => {
        cy.visit('https://jobs.test/connect');
        cy.get('#connect-login');

        cy.fixture('userData').then((user) => {
            cy.get('#connect-login input[name="email"]').type(user.email);
            cy.get('#connect-login input[name="password"]').type(user.password);
        });
        cy.contains('button', 'Login').click();

        cy.logout();
    });
});
