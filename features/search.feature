# features/search.feature
Feature: Search
  In order to see a word definition
  As a website user
  I need to be able to search for a word

  @javascript
  Scenario: Searching for a page that does exist
    Given I am on "http://127.0.0.1:8000"
    And I change window size to "1200" x "800"
    And I wait for "1" s
    And I press on "Se connecter"
    And I wait for "1" s
    And I fill in "_username" with "admin@admin.com"
    And I fill in "_password" with "admin"
    And I press on "Se connecter"