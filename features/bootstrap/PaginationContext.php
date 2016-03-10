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