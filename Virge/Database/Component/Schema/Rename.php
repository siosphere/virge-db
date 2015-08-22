<?php
namespace Virge\Database\Component\Schema;

use Virge\Core\Model;

/**
 * 
 */
class Rename extends Model{
    public function getQuery(){
        $sql = "CHANGE `{$this->field_name}` `{$this->new_field_name}` {$this->data_type}";
        
        return $sql;
    }
}