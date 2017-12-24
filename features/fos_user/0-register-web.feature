@web @login
Feature: Register new administrator
  In order to be able to handle administrative tasks
  As the system administrator
  I need to be able to register with the site

  Scenario: As an ordinary visitor, I should not be able to access the registration page
    Given I am not logged in
    And I visit "/register/"
    Then I should be on "/login"

  Scenario: As a system administrator, I should be able to access the registration page
    Given I am authorized with ROLE_ADMIN
    And I visit "/register/"
    Then I should be on "/register/"