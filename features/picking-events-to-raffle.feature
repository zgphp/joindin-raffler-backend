@raffle @api
Feature:
  In order to give away cool prizes to our members that leave feedback
  As an organizer
  I need to create raffle by first selecting which meetups are eligible

  Background:
    Given we have these meetups in the system
      | id | title     | startDate  | endDate    |
      | 1  | Meetup #1 | 2017-01-19 | 2017-01-19 |
      | 2  | Meetup #2 | 2017-02-19 | 2017-02-19 |
      | 3  | Meetup #3 | 2017-03-19 | 2017-03-19 |
      | 4  | Meetup #4 | 2017-04-19 | 2017-04-19 |
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

  Scenario: Organizer can pick which meetups to include in raffle
    Given there are no raffles in the system
    When organizer picks to raffle meetups: "2,3,4"
    Then there should be 3 events on the raffle

  Scenario: Organizer can see how many comments would be in raffle
    Given there are no raffles in the system
    When organizer picks to raffle meetups: "2,3"
    Then there should be 9 comments on the raffle

