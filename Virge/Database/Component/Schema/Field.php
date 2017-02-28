<?php

namespace Virge\Database\Component\Schema;

use Virge\Core\Model;

/**
 * 
 */
class Field extends Model{
    protected $null = true;
    protected $increment = false;
    protected $type = NULL;
    protected $length = NULL;
    protected $precision = NULL;
    protected $default = NULL;
    protected $attributes = NULL;
    protected $index = NULL;
    protected $comments = NULL;
    protected $after = NULL;
    
    
    public function getQuery($alter = false){
        $sql = "`{$this->name}` ";
        if($this->getLength()){
            $length = $this->getLength();
            if($this->getPrecision()){
                $length .= ',' . $this->getPrecision();
            }
            $sql .= "{$this->type}({$length})";
        } else {
            $sql .= "{$this->type}";
        }
        
        if($this->null === true){
            $sql .= " NULL";
        } else {
            $sql .= " NOT NULL";
        }
        
        if($this->increment === TRUE){
            $sql .= " AUTO_INCREMENT";
        }

        if($alter && $this->after) {
            $sql .= " AFTER `{$this->after}`";
        }

        return $sql;
    }
    
    public function getIndexQuery(){
        $sql = "";
        switch($this->index){
            case 'PRIMARY':
                $sql .= " PRIMARY KEY (`{$this->name}`)";
                break;
            case 'UNIQUE':
                $sql .= " UNIQUE KEY `{$this->name}` (`{$this->name}`)";
                break;
            case 'INDEX':
                $sql .= " KEY `{$this->name}` (`{$this->name}`)";
                break;
        }
        return $sql;
    }
}