# 91哥 TDD 課程第一週作業

> 自動測試與 TDD 實務開發（第五梯）- 第一週作業專案

**繁體中文** | [English](README.en.md)

## 專案簡介

這是一個使用 **TDD（測試驅動開發）** 和 **BDD（行為驅動開發）** 方法論實作的分頁取值功能。本專案展示如何使用 Behat（PHP 的 BDD 框架）和 Gherkin 語法來描述需求並驅動開發。

## 作業需求

實作一個分頁計算功能，能夠：
- 將資料列表依指定數量（N）分組
- 計算每組中指定欄位（Column）的總和
- 回傳以逗號分隔的結果字串

### 需求來源

- [作業需求表單](https://docs.google.com/spreadsheets/d/1P0nImGpaUYpP4yt_ecIM3TV9HtA8aE7ZyrflGoWuzsc/edit#gid=0)

## 專案結構

```
.
├── README.md                   # 專案說明文件（中文）
├── README.en.md                # 專案說明文件（英文）
├── composer.json               # Composer 設定檔
├── behat.yml                   # Behat 設定檔
├── docs/                       # 學習文件目錄
│   ├── 01-TDD-基本概念與流程.md (中文)
│   ├── 01-TDD-Fundamentals-and-Workflow.md (English)
│   ├── 02-BDD-與-Behat-使用指南.md (中文)
│   ├── 02-BDD-and-Behat-Guide.md (English)
│   ├── 03-專案實作步驟.md (中文)
│   └── 03-Project-Implementation-Steps.md (English)
├── src/                        # 原始碼目錄
│   └── Cart.php               # 分頁計算核心類別
└── features/                   # Behat 測試目錄
    ├── bootstrap/             # Context 類別
    │   ├── FeatureContext.php
    │   └── PaginationContext.php
    └── pagination/            # 分頁功能測試
        └── fetch_value_by_pagination.feature
```

## 功能說明

### Gherkin 語法描述

```gherkin
Feature: 分頁取值
  In order to 依據條件分頁取值
  As a 使用者
  I want to 依照不同需求組合取得回傳值

  Background:
    Given 現有資料列表
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

  Scenario Outline: 以 N 筆一組，取 Column 總和
    When 以 <n> 筆資料為一組取 <column> 總和
    Then 得到結果 <result>

    Examples:
      | n | column  | result     |
      | 3 | cost    | 6,15,24,21 |
      | 4 | revenue | 50,66,60   |
```

### 測試案例說明

**測試案例 1**：以 3 筆為一組，計算 cost 總和
- 第一組（id 1-3）：1+2+3 = 6
- 第二組（id 4-6）：4+5+6 = 15
- 第三組（id 7-9）：7+8+9 = 24
- 第四組（id 10-11）：10+11 = 21
- **結果**：`6,15,24,21`

**測試案例 2**：以 4 筆為一組，計算 revenue 總和
- 第一組（id 1-4）：11+12+13+14 = 50
- 第二組（id 5-8）：15+16+17+18 = 66
- 第三組（id 9-11）：19+20+21 = 60
- **結果**：`50,66,60`

## 環境需求

- PHP >= 5.6
- Composer

## 安裝步驟

### 1. Clone 專案

```bash
git clone <repository-url>
cd 91TDD-day1-homework
```

### 2. 安裝相依套件

```bash
composer install
```

## 執行測試

### 執行 Behat 測試

```bash
# 執行所有測試
vendor/bin/behat

# 執行 pagination suite
vendor/bin/behat --suite=pagination

# 顯示詳細資訊
vendor/bin/behat -v
```

### 查看可用的步驟定義

```bash
vendor/bin/behat -dl
```

## 學習文件

本專案包含完整的 TDD/BDD 學習文件，位於 `docs/` 目錄，提供**繁體中文**和 **English** 雙語版本：

### 📚 TDD 基本概念與流程
- [中文版：01-TDD-基本概念與流程.md](docs/01-TDD-基本概念與流程.md)
- [English: 01-TDD-Fundamentals-and-Workflow.md](docs/01-TDD-Fundamentals-and-Workflow.md)

學習內容：
- TDD 是什麼？/ What is TDD?
- TDD 的三大法則 / The Three Laws of TDD
- Red-Green-Refactor 循環 / Red-Green-Refactor Cycle
- TDD vs 傳統開發流程 / TDD vs Traditional Development
- TDD 的優點與挑戰 / Benefits and Challenges
- 實務建議 / Practical Recommendations

### 📚 BDD 與 Behat 使用指南
- [中文版：02-BDD-與-Behat-使用指南.md](docs/02-BDD-與-Behat-使用指南.md)
- [English: 02-BDD-and-Behat-Guide.md](docs/02-BDD-and-Behat-Guide.md)

學習內容：
- BDD 基本概念 / BDD Fundamentals
- BDD vs TDD
- Gherkin 語法詳解 / Gherkin Syntax Deep Dive
  - Feature、Scenario、Given-When-Then
  - Background、Scenario Outline
  - Table（表格資料）/ Table Data
- Behat Context 實作 / Behat Context Implementation
- Behat 設定與命令 / Behat Configuration and Commands
- BDD 最佳實踐 / BDD Best Practices

### 📚 專案實作步驟
- [中文版：03-專案實作步驟.md](docs/03-專案實作步驟.md)
- [English: 03-Project-Implementation-Steps.md](docs/03-Project-Implementation-Steps.md)

學習內容：
- 完整的 TDD/BDD 實作流程 / Complete TDD/BDD Workflow
- 從需求分析到程式實作 / From Requirements to Implementation
- Red-Green-Refactor 實戰範例 / Red-Green-Refactor Examples
- 程式碼重構技巧 / Code Refactoring Techniques
- 常見問題與解決方案 / Common Issues and Solutions
- 延伸練習建議 / Extended Exercise Suggestions

## 核心程式碼

### Cart 類別 (src/Cart.php)

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

## TDD 開發流程示範

本專案嚴格遵循 TDD 的 Red-Green-Refactor 循環：

### 1. Red（紅燈）- 撰寫失敗的測試
- 建立 Feature 檔案描述需求
- 建立 Context 類別定義測試步驟
- 執行測試，確認失敗

### 2. Green（綠燈）- 實作最少程式碼讓測試通過
- 建立 Cart 類別
- 實作基本功能讓測試通過
- 執行測試，確認通過

### 3. Refactor（重構）- 改善程式碼品質
- 改善命名、結構
- 加入型別檢查
- 提取方法、消除重複
- 執行測試，確保重構沒有破壞功能

## 技術棧

- **PHP**：程式語言
- **Composer**：依賴管理
- **Behat**：BDD 測試框架
- **PHPUnit**：斷言庫
- **Gherkin**：自然語言測試描述語法

## 關鍵字

`tdd`, `bdd`, `behat`, `phpunit`, `php`, `testing`, `gherkin`, `test-driven-development`, `behavior-driven-development`

## 學習資源

- [Behat 官方文件](https://docs.behat.org/)
- [PHPUnit 官方文件](https://phpunit.de/)
- [Gherkin 語法參考](https://cucumber.io/docs/gherkin/)
- [91哥的 TDD 課程](https://tdd.best/)

## 作者

- **Bobo Chen** - [bobo52310@gmail.com](mailto:bobo52310@gmail.com)

## 授權

本專案僅供學習使用。

## 課程資訊

- **課程名稱**：自動測試與 TDD 實務開發
- **梯次**：第五梯
- **作業週次**：第一週
- **講師**：91哥（Joey Chen）

---

**Happy Testing with TDD/BDD! 🚀**
