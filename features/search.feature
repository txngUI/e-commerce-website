# features/search.feature
Feature: Search
  In order to see a word definition
  As a website user
  I need to be able to search for a word

  @javascript
  Scenario: Searching for a page that does exist
    Given I am on "http://localhost:8000"
    And I change window size to "1200" x "800"
    And I wait for "1" s
    And I press "Se connecter"
    And I wait for "1" s
    And I fill in "_username" with "admin@admin.com"
    And I fill in "_password" with "admin"
    And I press "Sign in"
    And I wait for "1" s
    And I follow "Le seigneur des anneaux"
    And I wait for "1" s
    And I press "Ajouter au panier"
    And I wait for "1" s
    And I follow "Effectuer la commande"