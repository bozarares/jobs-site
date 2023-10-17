describe('template spec', () => {
    it('passes', () => {
        cy.visit('https://jobs.test');
        cy.login('test@example.com', 'password');
    });
});
