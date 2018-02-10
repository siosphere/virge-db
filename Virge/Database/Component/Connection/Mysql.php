<?php
namespace Virge\Database\Component\Connection;

use Virge\Database\Component\Connection\Mysql\Driver;
use Virge\Database\Component\Connection\Mysql\Statement;
use Virge\Database\Exception\InvalidQueryException;

/**
 * 
 * @author Michael Kramer
 */
class Mysql extends \Virge\Database\Component\Connection {
    
    /**
     * Holds the database resource
     * @var \Virge\Database\Component\Connection\Mysql\Driver 
     */
    protected $_resource;
    
    /**
     * Connect to our MySQL database
     */
    public function connect() {
        
        $host = $this->getHostname();
        $user = $this->getUsername();
        $password = $this->getPassword();
        $database = $this->getDatabase();
        $port = $this->getPort();
        $socket = $this->getSocket();
        
        $this->_resource = @new \PDO(sprintf('mysql:host=%s;port=%s;dbname=%s', $host, $port, $database), $user, $password, $this->getParameters());

        return true;
    }
    
    /**
     * Prepare and execute a statement and return the results if any
     * @param string $sql
     * @param array $params
     * @return boolean|array
     */
    public function query($sql, $params = array()) 
    {
        $stmt = $this->prepare($sql, $params);
        
        $stmt->execute();
        
        if($stmt->error) {
            $this->setError($stmt->error);
            $stmt->close();
            return false;
        }

        return $stmt->fetchAll();
    }

    public function ping()
    {
        return $this->_resource->ping();
    }

    public function insertId() : int
    {
        return intval($this->_resource->lastInsertId());
    }

    public function beginTransaction()
    {
        return $this->_resource->beginTransaction();
    }

    public function inTransaction()
    {
        return $this->_resource->inTransaction();
    }

    public function commit()
    {
        return $this->_resource->commit();
    }

    public function rollBack()
    {
        return $this->_resource->rollBack();
    }
    
    /**
     * Prepare a statement
     * @param string $sql
     * @param array $params
     * @throws InvalidQueryException
     */
    public function prepare($sql, $params = [], $options = []) : Statement
    {
        try {
            $stmt = $this->_resource->prepare($sql, $options);
        } catch(\PDOException $ex) {
            $error = $ex->getMessage();
            throw new InvalidQueryException(sprintf("Failed to execute query %s, error: %s", $sql, $ex->getMessage()));
        }

        if(!$stmt) {
            $errorInfo = $this->_resource->errorInfo();

            throw new InvalidQueryException(sprintf("Failed to execute query %s, error: [%s] (%s): %s", $sql, $errorInfo[0], $errorInfo[1], $errorInfo[2]));
        }

        return new Statement($stmt, $params);
    }
}