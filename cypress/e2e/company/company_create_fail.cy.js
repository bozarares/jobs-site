describe('template spec', () => {
    it('Test Company Create Fail', () => {
        cy.visit('https://jobs.test');
        cy.login('test@example.com', 'password');
        cy.visit('https://jobs.test/try-recruiting');
        cy.wait(1000);
        cy.contains('button', 'Create').click();
        cy.contains('div', 'The name field is required.').should('be.visible');
        cy.contains('div', 'The code field is required.').should('be.visible');
        cy.contains('div', 'The address field is required.').should(
            'be.visible',
        );
        cy.contains('div', 'The logo field is required.').should('be.visible');
    });
});
