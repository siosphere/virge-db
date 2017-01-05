<?php

namespace Virge\Database\Component\Schema;

use Virge\Core\Model;

/**
 * 
 */
class Index extends Model
{
    protected $type = 'KEY';
    protected $columns = array();
    protected $name = NULL;
    
    
    public function getName(){
        if($this->name == NULL){
            $columns = $this->getColumns();
            $key_name = implode('_', $columns);
            $this->name = 'KEY_' . $key_name;
        }
        return $this->name;
    }

    public function getQuery()
    {
        $sql = "ALTER TABLE  `{$this->getTable()}` ADD ";
        if($this->getType() === 'UNIQUE') {
            $sql .= "UNIQUE ";
        }
        
        $indexStatement = implode(',', array_map(function($column) {
            return "`{$column}`";
        }, $this->getColumns()));

        $sql .= "INDEX `{$this->getName()}` ({$indexStatement});";

        return $sql;
    }
}