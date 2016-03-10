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

    /**
     * @param mixed $n
     */
    public function setN($n)
    {
        $this->n = $n;
    }

    public function setColumn($column)
    {
        $this->column = $column;
    }


}