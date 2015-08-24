<?php
namespace Virge\Database\Service;

use Virge\Core\Config;
use Virge\Database;
use Virge\Database\Component\Connection\Mysql;
use Virge\Database\Component\Connection\Mysql\Statement;
use Virge\Database\Exception\ConnectionException;

/**
 * 
 * @author Michael Kramer
 */
class DatabaseService {
    
    /**
     * 
     * @param string $sql
     * @param array $params
     * @return boolean|array
     */
    public function query($sql, $params = array()) {
        return Database::connection('default')->query($sql, $params);
    }
    
    /**
     * 
     * @param string $sql
     * @param array $params
     * @return Statement
     */
    public function prepare($sql, $params = array()) {
        return Database::connection('default')->prepare($sql, $params);
    }
    
    /**
     * 
     * @param string $name
     */
    public function createConnection($name) {
        //TODO: determine connection type etc
        
        $connection = new Mysql();
        
        $config = Config::get('database');
        $connectionsConfig = $config['connections'];
        
        if(!isset($connectionsConfig[$name])){
            throw new \InvalidArgumentException(sprintf("Invalid connection %s was not defined", $name));
        }
        //TODO: defaults
        $connectionConfig = $connectionsConfig[$name];
        
        $connection->setName($name);
        $connection->setHostname($connectionConfig['hostname']);
        $connection->setUsername($connectionConfig['username']);
        $connection->setPassword($connectionConfig['password']);
        $connection->setDatabase($connectionConfig['database']);
        //set additional parameters
        
        if(!$connection->connect()) {
            throw new ConnectionException(sprintf("Failed to connect to %s, error: %s", $name, $connection->getError()));
        }
        
        return $connection;
    }
}