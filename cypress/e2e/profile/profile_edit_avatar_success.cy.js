describe('template spec', () => {
    it('Edit Profile Avatar Success', () => {
        cy.intercept('POST', '/upload/process').as('uploadRequest');

        cy.visit('https://jobs.test');
        cy.login('test@example.com', 'password');
        cy.visit('https://jobs.test/profile');
        cy.get(`#edit-profile-button`).click();
        cy.get(`#edit-avatar-button`).click();

        cy.get('input[type=file]').attachFile('logo-black.png', {
            force: true,
        });
        cy.wait('@uploadRequest').its('response.statusCode').should('eq', 200);
        cy.wait(2000);
        cy.contains('button', 'Save').click();
    });
});
