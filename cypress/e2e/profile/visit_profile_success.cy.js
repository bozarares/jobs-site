describe('template spec', () => {
    it('Test Profile Visit Success', () => {
        cy.visit('https://jobs.test');
        cy.login('test@example.com', 'password');
        cy.visit('https://jobs.test/profile');
    });
});
