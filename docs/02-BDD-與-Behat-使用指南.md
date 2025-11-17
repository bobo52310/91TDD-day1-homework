# BDD 與 Behat 使用指南

## 什麼是 BDD？

BDD（Behavior-Driven Development，行為驅動開發）是 TDD 的延伸，強調：

- **用自然語言描述系統行為**
- **促進開發團隊、測試團隊、業務人員之間的溝通**
- **從使用者角度出發撰寫測試**

## BDD vs TDD

| 項目 | TDD | BDD |
|------|-----|-----|
| 焦點 | 測試程式碼功能 | 測試系統行為 |
| 語言 | 技術性（程式碼） | 業務性（自然語言） |
| 參與者 | 開發人員 | 開發、測試、業務人員 |
| 範例 | `testAddUser()` | `當使用者註冊時，系統應該發送歡迎郵件` |

## 什麼是 Behat？

Behat 是 PHP 的 BDD 框架，類似 Ruby 的 Cucumber。它使用 **Gherkin 語法**來描述系統行為。

### Behat 的核心組件

1. **Feature（功能）**：描述要測試的功能
2. **Scenario（場景）**：具體的測試案例
3. **Context（上下文）**：將 Gherkin 步驟對應到 PHP 程式碼

## Gherkin 語法

Gherkin 是一種結構化的自然語言，用於描述軟體行為。

### 基本結構

```gherkin
Feature: 功能標題
  In order to 達成某個目標
  As a 某種角色
  I want to 執行某個動作

  Scenario: 場景標題
    Given 前置條件
    When 執行動作
    Then 預期結果
```

### 關鍵字說明

- **Feature**：描述要測試的功能模組
- **Scenario**：一個具體的測試案例
- **Given**：設定前置條件（背景資料、初始狀態）
- **When**：執行動作（使用者操作、觸發事件）
- **Then**：驗證結果（預期的系統行為）
- **And / But**：連接多個相同類型的步驟

### 範例：使用者登入功能

```gherkin
Feature: 使用者登入
  In order to 存取個人資料
  As a 註冊使用者
  I want to 能夠登入系統

  Scenario: 使用正確的帳號密碼登入
    Given 我是一個已註冊的使用者
    And 我的帳號是 "user@example.com"
    And 我的密碼是 "password123"
    When 我輸入帳號和密碼
    And 我點擊登入按鈕
    Then 我應該看到歡迎訊息
    And 我應該被導向到首頁

  Scenario: 使用錯誤的密碼登入
    Given 我是一個已註冊的使用者
    When 我輸入錯誤的密碼
    And 我點擊登入按鈕
    Then 我應該看到錯誤訊息 "帳號或密碼錯誤"
    And 我應該停留在登入頁面
```

## Background（背景）

當多個 Scenario 有共同的前置條件時，可以使用 Background：

```gherkin
Feature: 購物車功能

  Background:
    Given 我已經登入系統
    And 購物車中有以下商品
      | 商品名稱 | 數量 | 單價 |
      | 筆記本   | 2    | 50   |
      | 原子筆   | 5    | 10   |

  Scenario: 查看購物車總金額
    When 我查看購物車
    Then 總金額應該是 150 元

  Scenario: 移除商品
    When 我移除 "筆記本"
    Then 購物車應該只剩 1 種商品
```

## Scenario Outline（場景大綱）

用於執行多組類似的測試資料：

```gherkin
Feature: 計算機

  Scenario Outline: 加法運算
    Given 我有一個計算機
    When 我輸入 <數字1> 加 <數字2>
    Then 結果應該是 <結果>

    Examples:
      | 數字1 | 數字2 | 結果 |
      | 1     | 1     | 2    |
      | 2     | 3     | 5    |
      | 10    | 20    | 30   |
```

## Table（表格）

Gherkin 支援表格資料：

```gherkin
Feature: 使用者管理

  Scenario: 批次建立使用者
    Given 系統中有以下使用者
      | 姓名   | Email              | 角色    |
      | 張三   | zhang@example.com  | admin   |
      | 李四   | li@example.com     | user    |
      | 王五   | wang@example.com   | user    |
    When 我查看使用者列表
    Then 應該顯示 3 位使用者
```

## Behat Context（上下文）

Context 是 PHP 類別，負責將 Gherkin 步驟轉換為實際的測試程式碼。

### Context 範例

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
     * @Given /^我有一個計算機$/
     */
    public function 我有一個計算機()
    {
        $this->calculator = new Calculator();
    }

    /**
     * @When /^我輸入 (\\d+) 加 (\\d+)$/
     */
    public function 我輸入數字相加($num1, $num2)
    {
        $this->result = $this->calculator->add($num1, $num2);
    }

    /**
     * @Then /^結果應該是 (\\d+)$/
     */
    public function 結果應該是($expected)
    {
        Assert::assertEquals($expected, $this->result);
    }
}
```

### 正規表達式匹配

Behat 使用正規表達式來匹配 Gherkin 步驟：

- `(.*)` - 匹配任意字串
- `(\\d+)` - 匹配數字
- `"([^"]*)"` - 匹配引號內的字串

## Behat 配置檔案

`behat.yml` 用於設定 Behat 的行為：

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
        # 可以載入擴充套件
```

## Behat 命令

### 執行所有測試

```bash
vendor/bin/behat
```

### 執行特定 Suite

```bash
vendor/bin/behat --suite=calculator
```

### 執行特定 Feature

```bash
vendor/bin/behat features/calculator/add.feature
```

### 顯示可用的步驟定義

```bash
vendor/bin/behat -dl
```

### 產生步驟定義範本

```bash
vendor/bin/behat --dry-run --append-snippets
```

## 本專案的 BDD 實作

### 專案結構

```
features/
├── bootstrap/
│   ├── FeatureContext.php
│   └── PaginationContext.php
└── pagination/
    └── fetch_value_by_pagination.feature
```

### Feature 檔案

```gherkin
Feature: 分頁取值
  In order to 依據條件分頁取值
  As a 使用者
  I want to 依照不同需求組合取得回傳值

  Background:
    Given 現有資料列表
      | id | cost | revenue | sellprice |
      | 1  | 1    | 11      | 21        |
      # ... 更多資料

  Scenario Outline: 以 N 筆一組，取 Column 總和
    When 以 <n> 筆資料為一組取 <column> 總和
    Then 得到結果 <result>

    Examples:
      | n | column  | result     |
      | 3 | cost    | 6,15,24,21 |
      | 4 | revenue | 50,66,60   |
```

### Context 實作

```php
class PaginationContext implements Context
{
    private $data_source;
    private $count;

    /**
     * @Given /^現有資料列表$/
     */
    public function 現有資料列表(TableNode $table)
    {
        $this->data_source = $table->getColumnsHash();
    }

    /**
     * @When /^以 (.*) 筆資料為一組取 (.*) 總和$/
     */
    public function 以N筆資料為一組取Column總和($n, $column)
    {
        $cart = new Cart($this->data_source);
        $cart->setN($n);
        $cart->setColumn($column);
        $this->count = $cart->getCount();
    }

    /**
     * @Then /^得到結果 (.*)$/
     */
    public function 得到結果($result)
    {
        PHPUnit_Framework_Assert::assertEquals($result, $this->count);
    }
}
```

## BDD 最佳實踐

1. **使用業務語言**：避免技術術語，讓非技術人員也能理解
2. **一個 Scenario 測試一個行為**：保持測試的單一職責
3. **Given-When-Then 順序**：嚴格遵守這個結構
4. **避免 UI 細節**：專注在行為而非實作細節
5. **保持 Feature 簡潔**：太長的 Feature 應該拆分

## 參考資源

- [Behat 官方文件](https://docs.behat.org/)
- [Gherkin 語法參考](https://cucumber.io/docs/gherkin/)
- [BDD 實踐指南](https://cucumber.io/docs/bdd/)
