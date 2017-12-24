@web @login
Feature: Show registered user their profile page
  In order to see my profile page
  As a registered user
  I need to log in first

  Scenario: As a registered user, I need to log in to see my profile page
    Given I am on "/login"
    And I fill in "_username" with "admin"
    And I fill in "_password" with "admin"
    And I press "Log in"
    Then I should be on "/"
    And I visit "/profile"
    And I should see text matching "Logged in as admin"
    And I should see text matching "Username: admin"