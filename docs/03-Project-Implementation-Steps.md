# Project Implementation Steps

This document provides a detailed explanation of how to implement the "Pagination Value Fetch" feature using TDD/BDD methodology.

## Project Requirements

Implement a pagination calculation feature that can:
- Group data by a specified number
- Calculate the sum of a specified column for each group
- Return a comma-separated result string

### Requirement Example

Given the following data:

| id | cost | revenue | sellprice |
|----|------|---------|-----------|
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

**Test Case 1**: Group by 3, calculate cost sum
- Group 1: 1+2+3 = 6
- Group 2: 4+5+6 = 15
- Group 3: 7+8+9 = 24
- Group 4: 10+11 = 21
- **Result**: `6,15,24,21`

**Test Case 2**: Group by 4, calculate revenue sum
- Group 1: 11+12+13+14 = 50
- Group 2: 15+16+17+18 = 66
- Group 3: 19+20+21 = 60
- **Result**: `50,66,60`

## Implementation Steps

### Step 1: Environment Setup

#### 1.1 Initialize Composer Project

```bash
composer init
```

#### 1.2 Install Dependencies

```bash
composer require --dev phpunit/phpunit:^4.8
composer require --dev behat/behat:^3.0
```

#### 1.3 Configure Composer Autoload

Edit `composer.json`:

```json
{
    "autoload": {
        "psr-4": {
            "Tdd91\\": "src/"
        }
    }
}
```

Run:

```bash
composer dump-autoload
```

### Step 2: Create Project Structure

```bash
mkdir -p src
mkdir -p features/bootstrap
mkdir -p features/pagination
```

Project structure:

```
.
├── composer.json
├── composer.lock
├── behat.yml
├── src/
│   └── Cart.php
└── features/
    ├── bootstrap/
    │   ├── FeatureContext.php
    │   └── PaginationContext.php
    └── pagination/
        └── fetch_value_by_pagination.feature
```

### Step 3: Write Feature File (Red)

Create `features/pagination/fetch_value_by_pagination.feature`:

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

**Explanation**:
- Use `Background` to define shared precondition data
- Use `Scenario Outline` and `Examples` to run multiple test sets
- Use `TableNode` to pass table data

### Step 4: Initialize Behat

```bash
vendor/bin/behat --init
```

This creates the basic `FeatureContext.php`.

### Step 5: Create Context Class (Red)

Create `features/bootstrap/PaginationContext.php`:

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

**Explanation**:
- `TableNode::getColumnsHash()` converts table to array
- Regular expression `(.*)` captures parameters
- Use PHPUnit's Assert for verification

### Step 6: Configure behat.yml

Create `behat.yml`:

```yaml
default:
    suites:
        pagination:
            paths:    [ %paths.base%/features/pagination/fetch_value_by_pagination.feature ]
            contexts: [ PaginationContext ]
```

### Step 7: Run Tests (Should Fail - Red)

```bash
vendor/bin/behat --suite=pagination
```

**Expected Result**: Test fails because the `Cart` class doesn't exist yet.

### Step 8: Implement Cart Class (Green)

Create `src/Cart.php`:

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
        // Use array_chunk to group array
        $group = array_chunk($this->data_source, $this->n);

        // Calculate sum for each group
        foreach ($group as $item) {
            $ans = 0;
            foreach ($item as $it) {
                $ans = $ans + (int)$it[$this->column];
            }
            $result[] = $ans;
        }

        // Return comma-separated string
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

**Implementation Explanation**:
1. Use `array_chunk()` to group data by N items
2. Accumulate the values of the specified column for each group
3. Use `implode()` to convert result array to comma-separated string

### Step 9: Run Tests Again (Should Pass - Green)

```bash
vendor/bin/behat --suite=pagination
```

**Expected Result**: All tests pass (green light).

### Step 10: Refactor

Review the code, possible refactoring directions:

#### 10.1 Add Input Validation

```php
public function setN($n)
{
    if (!is_numeric($n) || $n <= 0) {
        throw new InvalidArgumentException('N must be a positive integer');
    }
    $this->n = (int)$n;
}

public function setColumn($column)
{
    if (empty($column)) {
        throw new InvalidArgumentException('Column name cannot be empty');
    }
    $this->column = $column;
}
```

#### 10.2 Improve Naming

```php
public function getCount()
{
    $groups = array_chunk($this->data_source, $this->n);
    $sums = [];

    foreach ($groups as $group) {
        $sums[] = $this->calculateGroupSum($group);
    }

    return implode(',', $sums);
}

private function calculateGroupSum($group)
{
    $sum = 0;
    foreach ($group as $row) {
        $sum += (int)$row[$this->column];
    }
    return $sum;
}
```

#### 10.3 Add Type Declarations (PHP 7+)

```php
public function __construct(array $data_source)
{
    $this->data_source = $data_source;
}

public function setN(int $n): void
{
    $this->n = $n;
}

public function setColumn(string $column): void
{
    $this->column = $column;
}

public function getCount(): string
{
    // ...
}
```

### Step 11: Confirm Tests Still Pass After Refactoring

```bash
vendor/bin/behat --suite=pagination
```

**Important**: Run tests after each refactoring to ensure functionality hasn't been broken.

## TDD Cycle Summary

In this project, we followed the TDD Red-Green-Refactor cycle:

1. **Red (Red Light)**
   - Write Feature file describing requirements
   - Create Context class calling the non-existent Cart class
   - Run tests, confirm failure

2. **Green (Green Light)**
   - Implement Cart class using the simplest approach to pass tests
   - Run tests, confirm passing

3. **Refactor**
   - Improve code quality (naming, structure, validation)
   - Run tests to ensure refactoring didn't break functionality

## Execution and Verification

### Run Behat Tests

```bash
# Run all tests
vendor/bin/behat

# Run specific suite
vendor/bin/behat --suite=pagination

# Display detailed information
vendor/bin/behat -v
```

### View Covered Steps

```bash
vendor/bin/behat -dl
```

## Common Issues

### Q1: Behat Can't Find Context Class

**Solution**: Verify that `contexts` configuration in `behat.yml` is correct.

### Q2: Cart Class Not Found

**Solution**: Run `composer dump-autoload` to regenerate autoload files.

### Q3: Tests Pass But Results Are Incorrect

**Solution**: Check if Examples data in Feature is correct, and verify assertion logic in Context.

## Key Learning Points

1. **Write tests first, then implementation**: This is the core of TDD
2. **Use natural language to describe requirements**: Gherkin makes tests living documentation
3. **Take small steps**: Implement only one test case at a time
4. **Run tests frequently**: Ensure each step is correct
5. **Refactoring needs test protection**: Only with tests can you refactor confidently

## Extended Exercises

1. **Add More Test Cases**
   - Test sellprice column
   - Test different grouping numbers

2. **Handle Edge Cases**
   - Empty data
   - N greater than data count
   - Non-existent column

3. **Add Error Handling Tests**
   - Use Scenario to test exception cases
   - Verify error messages

4. **Integrate PHPUnit**
   - Write unit tests for Cart class
   - Compare differences between BDD tests and unit tests

## References

- [Behat Official Documentation](https://docs.behat.org/)
- [PHPUnit Official Documentation](https://phpunit.de/)
- [Gherkin Syntax Reference](https://cucumber.io/docs/gherkin/)
