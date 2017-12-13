@web @login
Feature: Register new administrator
  In order to be able to handle administrative tasks
  As the system administrator
  I need to be able to register with the site

  Scenario: As an ordinary visitor, I should be able to register with the site
    Given there is no user with username "admin.primus"
    And I am on "/register/"
    When I fill in "fos_user_registration_form[email]" with "admin@prim.us"
    And I fill in "fos_user_registration_form[username]" with "admin.primus"
    And I fill in "fos_user_registration_form[plainPassword][first]" with "12345"
    And I fill in "fos_user_registration_form[plainPassword][second]" with "12345"
    And I press "Register"
    Then I should be on "/register/confirmed"
    And I should see text matching "Logged in as admin.primus"
    And I should see text matching "Congrats admin.primus, your account is now activated."