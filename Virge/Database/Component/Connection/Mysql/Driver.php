<?php
namespace Virge\Database\Component\Connection\Mysql;
/**
 * 
 * @author Michael Kramer
 */

class Driver extends \mysqli {
    
    /**
     * Override the prepare so we can support fetch_assoc
     * @param string $query
     * @return \Virge\Database\Component\Connection\Mysql\Statement
     */
    public function prepare($query)
    {
        $stmt = new Statement($this, $query);

        return $stmt;
    }
}