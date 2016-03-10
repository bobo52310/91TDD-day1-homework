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

}