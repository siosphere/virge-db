<?php
namespace Virge\Database\Component\Schema\Field;

/**
 * 
 */
class Id extends \Virge\Database\Component\Schema\Field{
    protected $null = false;
    protected $increment = true;
    protected $type = 'INT';
    protected $length = 11;
    protected $precision = NULL;
    protected $default = false;
    protected $attributes = NULL;
    protected $index = 'PRIMARY';
    protected $comments = NULL;
}