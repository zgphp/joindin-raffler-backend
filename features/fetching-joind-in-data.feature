# This file contains a user story for demonstration only.
# Learn how to get started with Behat and BDD on Behat's website:
# http://behat.org/en/latest/quick_start.html

@fetch @api
Feature:
  In order to pick meetups to raffle
  As an organizer
  I need to have all meetups,talks and comments in

  Scenario: It fetches meetups from Joind.in
    Given there are no meetups in the system
    When I fetch meetup data from Joind.in
    Then there should be 24 ZgPHP meetups in system

  Scenario: It fetches talks from Joind.in meetups we have in our system
    Given we have these meetups in the system
      | id   | title         | date       |
      | 6674 | ZgPHP 2017/10 | 2017-10-19 |
    When I fetch meetup talks from Joind.in
    Then there should be 2 talks in system

  Scenario: It fetches comments from Joind.in talks we have in our system
    Given we have these meetups in the system
      | id   | title         | date       |
      | 6674 | ZgPHP 2017/10 | 2017-10-19 |
    And we have these talks in the system
      | id    | title              | eventId | importedAt          |
      | 22817 | Fullstacking - 101 | 6674    | 2020-01-02 11:22:33 |
    When I fetch meetup talk comments from Joind.in
    Then there should be 1 comment in system
