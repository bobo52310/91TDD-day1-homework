# BDD and Behat Guide

## What is BDD?

BDD (Behavior-Driven Development) is an extension of TDD that emphasizes:

- **Describing system behavior in natural language**
- **Facilitating communication between development, testing, and business teams**
- **Writing tests from the user's perspective**

## BDD vs TDD

| Aspect | TDD | BDD |
|--------|-----|-----|
| Focus | Testing code functionality | Testing system behavior |
| Language | Technical (code) | Business-oriented (natural language) |
| Participants | Developers | Developers, testers, business people |
| Example | `testAddUser()` | `When a user registers, the system should send a welcome email` |

## What is Behat?

Behat is a BDD framework for PHP, similar to Ruby's Cucumber. It uses **Gherkin syntax** to describe system behavior.

### Core Components of Behat

1. **Feature**: Describes the functionality to be tested
2. **Scenario**: Specific test cases
3. **Context**: Maps Gherkin steps to PHP code

## Gherkin Syntax

Gherkin is a structured natural language used to describe software behavior.

### Basic Structure

```gherkin
Feature: Feature Title
  In order to achieve some goal
  As a certain role
  I want to perform some action

  Scenario: Scenario Title
    Given preconditions
    When action is performed
    Then expected result
```

### Keyword Explanation

- **Feature**: Describes the feature module to be tested
- **Scenario**: A specific test case
- **Given**: Set preconditions (background data, initial state)
- **When**: Perform action (user operation, trigger event)
- **Then**: Verify result (expected system behavior)
- **And / But**: Connect multiple steps of the same type

### Example: User Login Feature

```gherkin
Feature: User Login
  In order to access personal data
  As a registered user
  I want to be able to log into the system

  Scenario: Login with correct credentials
    Given I am a registered user
    And my email is "user@example.com"
    And my password is "password123"
    When I enter my email and password
    And I click the login button
    Then I should see a welcome message
    And I should be redirected to the homepage

  Scenario: Login with incorrect password
    Given I am a registered user
    When I enter an incorrect password
    And I click the login button
    Then I should see an error message "Incorrect email or password"
    And I should remain on the login page
```

## Background

When multiple scenarios share common preconditions, use Background:

```gherkin
Feature: Shopping Cart

  Background:
    Given I am logged into the system
    And my cart contains the following items
      | Product Name | Quantity | Unit Price |
      | Notebook     | 2        | 50         |
      | Pen          | 5        | 10         |

  Scenario: View cart total
    When I view my cart
    Then the total should be 150 dollars

  Scenario: Remove item
    When I remove "Notebook"
    Then the cart should only contain 1 type of item
```

## Scenario Outline

Used to run multiple sets of similar test data:

```gherkin
Feature: Calculator

  Scenario Outline: Addition operation
    Given I have a calculator
    When I enter <number1> plus <number2>
    Then the result should be <result>

    Examples:
      | number1 | number2 | result |
      | 1       | 1       | 2      |
      | 2       | 3       | 5      |
      | 10      | 20      | 30     |
```

## Table

Gherkin supports table data:

```gherkin
Feature: User Management

  Scenario: Batch create users
    Given the system has the following users
      | Name  | Email              | Role  |
      | John  | john@example.com   | admin |
      | Mary  | mary@example.com   | user  |
      | Peter | peter@example.com  | user  |
    When I view the user list
    Then 3 users should be displayed
```

## Behat Context

Context is a PHP class responsible for converting Gherkin steps into actual test code.

### Context Example

```php
<?php
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use PHPUnit\Framework\Assert;

class CalculatorContext implements Context
{
    private $calculator;
    private $result;

    /**
     * @Given /^I have a calculator$/
     */
    public function iHaveACalculator()
    {
        $this->calculator = new Calculator();
    }

    /**
     * @When /^I enter (\\d+) plus (\\d+)$/
     */
    public function iEnterNumberPlusNumber($num1, $num2)
    {
        $this->result = $this->calculator->add($num1, $num2);
    }

    /**
     * @Then /^the result should be (\\d+)$/
     */
    public function theResultShouldBe($expected)
    {
        Assert::assertEquals($expected, $this->result);
    }
}
```

### Regular Expression Matching

Behat uses regular expressions to match Gherkin steps:

- `(.*)` - Match any string
- `(\\d+)` - Match numbers
- `"([^"]*)"` - Match string within quotes

## Behat Configuration File

`behat.yml` is used to configure Behat's behavior:

```yaml
default:
    suites:
        default:
            paths:    [ %paths.base%/features ]
            contexts: [ FeatureContext ]

        calculator:
            paths:    [ %paths.base%/features/calculator ]
            contexts: [ CalculatorContext ]

    extensions:
        # Load extensions
```

## Behat Commands

### Run All Tests

```bash
vendor/bin/behat
```

### Run Specific Suite

```bash
vendor/bin/behat --suite=calculator
```

### Run Specific Feature

```bash
vendor/bin/behat features/calculator/add.feature
```

### Display Available Step Definitions

```bash
vendor/bin/behat -dl
```

### Generate Step Definition Templates

```bash
vendor/bin/behat --dry-run --append-snippets
```

## BDD Implementation in This Project

### Project Structure

```
features/
├── bootstrap/
│   ├── FeatureContext.php
│   └── PaginationContext.php
└── pagination/
    └── fetch_value_by_pagination.feature
```

### Feature File

```gherkin
Feature: Pagination Value Fetch
  In order to fetch values based on pagination
  As a user
  I want to retrieve values based on different requirements

  Background:
    Given the following data list
      | id | cost | revenue | sellprice |
      | 1  | 1    | 11      | 21        |
      # ... more data

  Scenario Outline: Fetch column sum in groups of N
    When I group by <n> items and sum <column>
    Then I get the result <result>

    Examples:
      | n | column  | result     |
      | 3 | cost    | 6,15,24,21 |
      | 4 | revenue | 50,66,60   |
```

### Context Implementation

```php
class PaginationContext implements Context
{
    private $data_source;
    private $count;

    /**
     * @Given /^the following data list$/
     */
    public function theFollowingDataList(TableNode $table)
    {
        $this->data_source = $table->getColumnsHash();
    }

    /**
     * @When /^I group by (.*) items and sum (.*)$/
     */
    public function iGroupByItemsAndSum($n, $column)
    {
        $cart = new Cart($this->data_source);
        $cart->setN($n);
        $cart->setColumn($column);
        $this->count = $cart->getCount();
    }

    /**
     * @Then /^I get the result (.*)$/
     */
    public function iGetTheResult($result)
    {
        PHPUnit_Framework_Assert::assertEquals($result, $this->count);
    }
}
```

## BDD Best Practices

1. **Use business language**: Avoid technical jargon so non-technical people can understand
2. **One Scenario tests one behavior**: Keep tests focused with single responsibility
3. **Follow Given-When-Then order**: Strictly adhere to this structure
4. **Avoid UI details**: Focus on behavior rather than implementation details
5. **Keep Features concise**: Split features that are too long

## References

- [Behat Official Documentation](https://docs.behat.org/)
- [Gherkin Syntax Reference](https://cucumber.io/docs/gherkin/)
- [BDD Practice Guide](https://cucumber.io/docs/bdd/)
