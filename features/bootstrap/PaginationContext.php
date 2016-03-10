<?php
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use Tdd91\Cart;

class PaginationContext implements Context
{
    private $data_source;
    private $count;
}