# 91哥 TDD 課程第一週作業(自動測試與 TDD 實務開發 第五梯)

## 作業內容

第一週作業需求：
https://docs.google.com/spreadsheets/d/1P0nImGpaUYpP4yt_ecIM3TV9HtA8aE7ZyrflGoWuzsc/edit#gid=0

這邊嘗試使用 Gherkin 語法來描述需求：
```
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