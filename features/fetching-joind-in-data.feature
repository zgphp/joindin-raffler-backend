# This file contains a user story for demonstration only.
# Learn how to get started with Behat and BDD on Behat's website:
# http://behat.org/en/latest/quick_start.html

@fetch
Feature:
  In order to pick meetups to raffle
  As a organizer
  I need to have all meetups,talks and comments in

  @api
  Scenario: It fetches meetups from Joind.in
    Given there are no meetups in the system
    When I fetch meetup data from Joind.in
    Then there should be 24 ZgPHP meetups in system
