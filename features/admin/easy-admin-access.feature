@web @login
Feature: Prevent unauthorized access to admin backend
  In order to protect admin backend from unauthorized access
  As the system administrator
  I need to restrict access to admin only to users authorized as administrators

  Scenario: As an ordinary visitor, I should not be able to access the admin page
    Given I am not logged in
    And I visit "/admin/"
    Then I should be on "/login"

  Scenario: As a system administrator, I should be able to access the admin page
    Given I am authorized with ROLE_ADMIN
    And I visit "/admin/"
    Then I should be on "/admin/"