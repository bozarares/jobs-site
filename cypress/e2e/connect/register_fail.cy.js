describe('template spec', () => {
    it('Test Register Fails with connect', () => {
        cy.visit('https://jobs.test/connect');
        cy.get('#connect-register');
        cy.contains('div', 'I agree with terms and conditions')
            .should('be.visible')
            .click();

        cy.contains('button', 'Register').click();

        cy.contains('div', 'The name field is required.').should('be.visible');
        cy.contains('div', 'The email field is required.').should('be.visible');
        cy.contains('div', 'The password field is required.').should(
            'be.visible',
        );

        const password1 = `Password123`;
        const password2 = `123Password`;

        cy.get('#connect-register input[name="password"]').type(password1);

        cy.get('#connect-register input[name="password_confirmation"]').type(
            password2,
        );

        cy.contains('button', 'Register').click();

        cy.contains(
            'div',
            'The password field confirmation does not match.',
        ).should('be.visible');
    });
});
