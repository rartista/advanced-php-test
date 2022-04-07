<?php

class BaseController
{
    protected $args;

    public function __construct($args)
    {
        $this->args = $args;
    }
}
