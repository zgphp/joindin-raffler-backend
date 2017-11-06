@raffle @api
Feature:
  In order to give away cool prizes to our members that leave feedback
  As an organizer
  I need to mark users as winners and noshows

  Background:
    Given we have these meetups in the system
      | id | title     | startDate  | endDate    |
      | 1  | Meetup #1 | 2017-01-19 | 2017-01-19 |
      | 2  | Meetup #2 | 2017-02-19 | 2017-02-19 |
      | 3  | Meetup #3 | 2017-03-19 | 2017-03-19 |
    And we have these talks in the system
      | id  | title    | eventId | importedAt          |
      | 101 | Talk 101 | 1       | 2020-01-02 11:22:33 |
      | 201 | Talk 201 | 2       | 2020-01-02 11:22:33 |
      | 202 | Talk 202 | 2       | 2020-01-02 11:22:33 |
      | 301 | Talk 301 | 3       | 2020-01-02 11:22:33 |
    And we have these users in the system
      | id | username | displayName |
      | 1  | User1    | User 1      |
      | 2  | User2    | User 2      |
      | 3  | User3    | User 2      |
    And we have each user commenting on each talk
    And there are no raffles in the system
    And organizer picks to raffle meetups: "2,3"


  Scenario: Running a raffle with events that have comments on talks selects a user as a winner
    When we pick
    Then we should get back one of the members that left feedback

  Scenario: Selecting a user who submitted 3 comments removes 3 comments from the raffle
    When user "User1" wins
    Then there should be 6 comments on the raffle

  Scenario: Selecting a user who are not currently present removes their comments from the current raffle
    When user "User1" is no show
    Then there should be 6 comments on the raffle

  Scenario: Leaving comments for multiple talks on the same meetup increases the chances to win the raffle
    Then "User1" user should be 3 times in the list
    And "User2" user should be 3 times in the list
    And "User3" user should be 3 times in the list

