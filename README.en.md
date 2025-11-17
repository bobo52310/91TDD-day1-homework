# 91's TDD Course - Week 1 Homework

> Automated Testing and TDD Practice Development (5th Cohort) - Week 1 Project

[繁體中文](README.md) | **English**

## Project Overview

This is a pagination value fetch feature implemented using **TDD (Test-Driven Development)** and **BDD (Behavior-Driven Development)** methodologies. The project demonstrates how to use Behat (PHP's BDD framework) and Gherkin syntax to describe requirements and drive development.

## Assignment Requirements

Implement a pagination calculation feature that can:
- Group data list by specified number (N)
- Calculate the sum of specified column for each group
- Return comma-separated result string

### Requirement Source

- [Assignment Requirement Form](https://docs.google.com/spreadsheets/d/1P0nImGpaUYpP4yt_ecIM3TV9HtA8aE7ZyrflGoWuzsc/edit#gid=0)

## Project Structure

```
.
├── README.md                   # Project documentation (Chinese)
├── README.en.md                # Project documentation (English)
├── composer.json               # Composer configuration
├── behat.yml                   # Behat configuration
├── docs/                       # Learning materials directory
│   ├── 01-TDD-基本概念與流程.md (Chinese)
│   ├── 01-TDD-Fundamentals-and-Workflow.md (English)
│   ├── 02-BDD-與-Behat-使用指南.md (Chinese)
│   ├── 02-BDD-and-Behat-Guide.md (English)
│   ├── 03-專案實作步驟.md (Chinese)
│   └── 03-Project-Implementation-Steps.md (English)
├── src/                        # Source code directory
│   └── Cart.php               # Pagination calculation core class
└── features/                   # Behat test directory
    ├── bootstrap/             # Context classes
    │   ├── FeatureContext.php
    │   └── PaginationContext.php
    └── pagination/            # Pagination feature tests
        └── fetch_value_by_pagination.feature
```

## Feature Description

### Gherkin Syntax Description

```gherkin
Feature: Pagination Value Fetch
  In order to fetch values based on pagination
  As a user
  I want to retrieve values based on different requirements

  Background:
    Given the following data list
      | id | cost | revenue | sellprice |
      | 1  | 1    | 11      | 21        |
      | 2  | 2    | 12      | 22        |
      | 3  | 3    | 13      | 23        |
      | 4  | 4    | 14      | 24        |
      | 5  | 5    | 15      | 25        |
      | 6  | 6    | 16      | 26        |
      | 7  | 7    | 17      | 27        |
      | 8  | 8    | 18      | 28        |
      | 9  | 9    | 19      | 29        |
      | 10 | 10   | 20      | 30        |
      | 11 | 11   | 21      | 31        |

  Scenario Outline: Fetch column sum in groups of N
    When I group by <n> items and sum <column>
    Then I get the result <result>

    Examples:
      | n | column  | result     |
      | 3 | cost    | 6,15,24,21 |
      | 4 | revenue | 50,66,60   |
```

### Test Case Explanation

**Test Case 1**: Group by 3, calculate cost sum
- Group 1 (id 1-3): 1+2+3 = 6
- Group 2 (id 4-6): 4+5+6 = 15
- Group 3 (id 7-9): 7+8+9 = 24
- Group 4 (id 10-11): 10+11 = 21
- **Result**: `6,15,24,21`

**Test Case 2**: Group by 4, calculate revenue sum
- Group 1 (id 1-4): 11+12+13+14 = 50
- Group 2 (id 5-8): 15+16+17+18 = 66
- Group 3 (id 9-11): 19+20+21 = 60
- **Result**: `50,66,60`

## Requirements

- PHP >= 5.6
- Composer

## Installation

### 1. Clone the Project

```bash
git clone <repository-url>
cd 91TDD-day1-homework
```

### 2. Install Dependencies

```bash
composer install
```

## Running Tests

### Run Behat Tests

```bash
# Run all tests
vendor/bin/behat

# Run pagination suite
vendor/bin/behat --suite=pagination

# Display detailed information
vendor/bin/behat -v
```

### View Available Step Definitions

```bash
vendor/bin/behat -dl
```

## Learning Materials

This project includes comprehensive TDD/BDD learning materials in the `docs/` directory:

### 📚 [01-TDD-Fundamentals-and-Workflow.md](docs/01-TDD-Fundamentals-and-Workflow.md)

Learning Content:
- What is TDD?
- The Three Laws of TDD
- Red-Green-Refactor Cycle
- TDD vs Traditional Development
- Benefits and Challenges of TDD
- Practical Recommendations

### 📚 [02-BDD-and-Behat-Guide.md](docs/02-BDD-and-Behat-Guide.md)

Learning Content:
- BDD Fundamentals
- BDD vs TDD
- Gherkin Syntax Deep Dive
  - Feature, Scenario, Given-When-Then
  - Background, Scenario Outline
  - Table (Table Data)
- Behat Context Implementation
- Behat Configuration and Commands
- BDD Best Practices

### 📚 [03-Project-Implementation-Steps.md](docs/03-Project-Implementation-Steps.md)

Learning Content:
- Complete TDD/BDD Implementation Workflow
- From Requirements Analysis to Code Implementation
- Red-Green-Refactor Practical Examples
- Code Refactoring Techniques
- Common Issues and Solutions
- Extended Exercise Suggestions

### Chinese Version Available

All documentation is also available in Traditional Chinese:
- [01-TDD-基本概念與流程.md](docs/01-TDD-基本概念與流程.md)
- [02-BDD-與-Behat-使用指南.md](docs/02-BDD-與-Behat-使用指南.md)
- [03-專案實作步驟.md](docs/03-專案實作步驟.md)

## Core Code

### Cart Class (src/Cart.php)

```php
<?php

namespace Tdd91;

class Cart
{
    public $data_source;
    private $n;
    private $column;

    public function __construct(Array $data_source)
    {
        $this->data_source = $data_source;
    }

    public function getCount()
    {
        $group = array_chunk($this->data_source, $this->n);
        foreach ($group as $item) {
            $ans = 0;
            foreach ($item as $it) {
                $ans = $ans + (int)$it[$this->column];
            }
            $result[] = $ans;
        }
        return implode(',', $result);
    }

    public function setN($n)
    {
        $this->n = $n;
    }

    public function setColumn($column)
    {
        $this->column = $column;
    }
}
```

### PaginationContext (features/bootstrap/PaginationContext.php)

```php
<?php
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use Tdd91\Cart;

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

## TDD Development Workflow Demonstration

This project strictly follows the TDD Red-Green-Refactor cycle:

### 1. Red (Red Light) - Write Failing Tests
- Create Feature file describing requirements
- Create Context class defining test steps
- Run tests, confirm failure

### 2. Green (Green Light) - Implement Minimum Code to Pass Tests
- Create Cart class
- Implement basic functionality to pass tests
- Run tests, confirm passing

### 3. Refactor - Improve Code Quality
- Improve naming and structure
- Add type checking
- Extract methods, eliminate duplication
- Run tests to ensure refactoring doesn't break functionality

## Technology Stack

- **PHP**: Programming language
- **Composer**: Dependency management
- **Behat**: BDD testing framework
- **PHPUnit**: Assertion library
- **Gherkin**: Natural language test description syntax

## Keywords

`tdd`, `bdd`, `behat`, `phpunit`, `php`, `testing`, `gherkin`, `test-driven-development`, `behavior-driven-development`

## Learning Resources

- [Behat Official Documentation](https://docs.behat.org/)
- [PHPUnit Official Documentation](https://phpunit.de/)
- [Gherkin Syntax Reference](https://cucumber.io/docs/gherkin/)
- [91's TDD Course](https://tdd.best/)

## Author

- **Bobo Chen** - [bobo52310@gmail.com](mailto:bobo52310@gmail.com)

## License

This project is for learning purposes only.

## Course Information

- **Course Name**: Automated Testing and TDD Practice Development
- **Cohort**: 5th
- **Assignment Week**: Week 1
- **Instructor**: 91 (Joey Chen)

---

**Happy Testing with TDD/BDD! 🚀**
