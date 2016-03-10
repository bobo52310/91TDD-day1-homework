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
}