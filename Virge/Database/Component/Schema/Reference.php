<?php

namespace Virge\Database\Component\Schema;

use Virge\Core\Model;

/**
 * 
 */
class Reference extends Model{
    public function getQuery($database){
        $sql = "ALTER TABLE  `{$this->getTable()}` ADD FOREIGN KEY (  `{$this->getField()}` ) REFERENCES  `{$database}`.`{$this->getReferenceTable()}` (
            `{$this->getReferenceField()}`) ON DELETE SET NULL ON UPDATE CASCADE;";
        return $sql;
    }
}