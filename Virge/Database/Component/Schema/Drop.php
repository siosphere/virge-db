<?php
namespace Virge\Database\Component\Schema;

use Virge\Core\Model;

/**
 * 
 */
class Drop extends Model{
    public function getQuery(){
        $sql = "DROP COLUMN `{$this->name}` ";
    }
}