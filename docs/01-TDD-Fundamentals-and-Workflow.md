# TDD Fundamentals and Workflow

## What is TDD?

TDD (Test-Driven Development) is a software development methodology with the core concept: **write tests first, then write code**.

## The Three Laws of TDD

1. **Do not write any production code before writing a failing unit test**
2. **Write only enough of a unit test to make it fail (compilation failure counts as failure)**
3. **Write only enough production code to pass the currently failing test**

## The TDD Cycle: Red-Green-Refactor

TDD follows a simple development cycle:

```
Red → Green → Refactor → Red...
```

### 1. Red (Red Phase)

- **Write a failing test**
- The test should fail because the functionality hasn't been implemented yet
- Ensure the test actually fails to avoid "false positives"

**Example:**
```php
// Write the test first, code doesn't exist yet
public function testCalculateSum()
{
    $calculator = new Calculator();
    $result = $calculator->sum(2, 3);
    $this->assertEquals(5, $result);
}
```

### 2. Green (Green Phase)

- **Write the minimum amount of code to make the test pass**
- Don't worry about optimization or perfect design
- One goal only: make the test turn green

**Example:**
```php
class Calculator
{
    public function sum($a, $b)
    {
        return $a + $b;  // Simplest implementation
    }
}
```

### 3. Refactor (Refactor Phase)

- **Improve code quality while keeping tests passing**
- Eliminate duplicate code
- Improve naming, structure, and design patterns
- Ensure tests still pass after refactoring

**Example:**
```php
class Calculator
{
    public function sum($a, $b)
    {
        // Add type checking to improve code quality
        if (!is_numeric($a) || !is_numeric($b)) {
            throw new InvalidArgumentException('Arguments must be numeric');
        }
        return $a + $b;
    }
}
```

## Benefits of TDD

1. **High test coverage**: Every line of code is written to pass a test
2. **Better design**: Think about interface and usage before implementation details
3. **Fast feedback**: Know immediately if code is correct
4. **Refactoring confidence**: Tests protect you, allowing safe refactoring
5. **Living documentation**: Tests serve as usage documentation

## Challenges of TDD

1. **Learning curve**: Takes time to adapt to test-first mindset
2. **Initially slower**: May feel like development slows down at first
3. **Requires discipline**: Must strictly follow the TDD process
4. **Test maintenance**: Test code also needs maintenance

## TDD vs Traditional Development

| Aspect | Traditional Development | TDD |
|--------|------------------------|-----|
| Development Order | Code → Tests | Tests → Code |
| Testing Timing | Add tests after development | Write tests before development |
| Test Coverage | Usually lower | Usually higher |
| Refactoring Confidence | Lower | Higher |
| Design Quality | Easy to over-design or under-design | Just enough design |

## Practical Recommendations

1. **Start small**: Choose simple features to practice TDD
2. **Keep tests simple**: One test should test one thing
3. **Iterate quickly**: Red-Green-Refactor cycle should be fast
4. **Don't skip steps**: Strictly follow the three laws of TDD
5. **Practice continuously**: TDD is a skill that requires deliberate practice

## References

- *Test-Driven Development: By Example* by Kent Beck
- *The Art of Unit Testing* by Roy Osherove
- 91's TDD Course
