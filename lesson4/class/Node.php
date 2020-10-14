<?php

class Node
{
    public $value;
    public ?Node $left = null;
    public ?Node $right = null;

    public function __construct($value)
    {
        $this->value = $value;
    }
}
