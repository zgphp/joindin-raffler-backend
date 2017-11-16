@raffle @api
Feature:
  In order to give away cool prizes to our members that leave feedback
  As an organizer
  I need to create raffle by first selecting which meetups are eligible

  Scenario: Organizer can pick just one meetup to include in raffle
    Given we have these meetups in the system
      | id | title     | date       |
      | 1  | Meetup #1 | 2017-01-19 |
      | 2  | Meetup #2 | 2017-02-19 |
    And we have these talks in the system
      | id | title             | eventId |
      | 10 | Talk on meetup #1 | 2       |
    And we have these users in the system
      | id | username | displayName | organizer |
      | 1  | User1    | User 1      | false     |
    And we have each user commenting on each talk
    When organizer picks to raffle meetups: "2"
    Then there should be 1 events on the raffle

  Scenario: Organizer can pick multiple meetups to include in raffle
    Given we have these meetups in the system
      | id | title     | date       |
      | 1  | Meetup #1 | 2017-01-19 |
      | 2  | Meetup #2 | 2017-02-19 |
      | 3  | Meetup #3 | 2017-03-19 |
    And we have these talks in the system
      | id | title             | eventId |
      | 10 | Talk on meetup #1 | 1       |
    And we have these users in the system
      | id | username | displayName | organizer |
      | 1  | User1    | User 1      | false     |
    And we have each user commenting on each talk
    When organizer picks to raffle meetups: "1,3"
    Then there should be 2 events on the raffle

  Scenario: Organizer will try to start a raffle with no events
    Then we get an exception for a raffle with no meetups

  Scenario: Organizer will try to start a raffle with events not having any comments
    Given we have these uncommented meetups in the system
      | id | title     | date       |
      | 1  | Meetup #1 | 2017-01-19 |
      | 2  | Meetup #2 | 2017-02-19 |
      | 3  | Meetup #3 | 2017-03-19 |
    When organizer picks to raffle meetups: "1,3"
    Then we get an exception for a raffle with no comments
