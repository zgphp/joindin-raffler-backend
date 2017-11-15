@raffle @api
Feature:
  In order to give away cool prizes to our members that leave feedback
  As an organizer
  I need to pick a random person as a winner

  Scenario: Organizer will get the only member that was eligible for a prize
    Given we have a raffle with a single comment coming from "User1"
    When I pick a winner
    Then we should get "User1" as a winner

  Scenario: Organizer will get one of the members that was eligible for a prize
    Given we have these meetups in the system
      | id | title     | date       |
      | 1  | Meetup #1 | 2017-01-19 |
    And we have these talks in the system
      | id | title             | eventId |
      | 10 | Talk on meetup #1 | 1       |
    And we have these users in the system
      | id | username | displayName | organizer |
      | 1  | User1    | User 1      | false     |
      | 2  | User2    | User 2      | false     |
    And we have each user commenting on each talk
    And organizer picks to raffle meetups: "1"
    When I pick a winner
    Then we should get one of "User1,User2" as a winner

  Scenario: Organizer's comments will not be eligible for raffle
    Given we have these meetups in the system
      | id | title     | date       |
      | 1  | Meetup #1 | 2017-01-19 |
    And we have these talks in the system
      | id | title             | eventId |
      | 10 | Talk on meetup #1 | 1       |
    And we have these users in the system
      | id    | username   | displayName | organizer  |
      | 18486 | Organizer1 | Organizer 1 | true       |
    And we have each user commenting on each talk
    When organizer picks to raffle meetups: "1"
    Then there should be 0 comments on the raffle