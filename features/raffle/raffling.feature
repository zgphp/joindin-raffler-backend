@raffle @api
Feature:
  In order to give away cool prizes to our members that leave feedback
  As an organizer
  I need to pick a random person as a winner

  Scenario: Organizer will get the only member that was eligible for a prize
    Given we have a raffle with a single comment coming from "User1"
    When I pick a winner
    Then we should get "User1" as a winner
