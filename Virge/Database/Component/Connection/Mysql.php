<?php
namespace Virge\Database\Component\Connection;

use Virge\Database\Component\Connection\Mysql\Driver;
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
        
        $this->_resource = @new Driver($host, $user, $password, $database, $port, $socket);
        
        if($this->_resource->connect_error) {
            $this->setError($this->_resource->connect_error);
            return false;
        }
        
        return true;
    }
    
    /**
     * Prepare and execute a statement and return the results if any
     * @param string $sql
     * @param array $params
     * @return boolean|array
     */
    public function query($sql, $params = array()) {
        $stmt = $this->prepare($sql, $params);
        
        $stmt->execute();
        
        if($stmt->error) {
            $this->setError($stmt->error);
            $stmt->close();
            return false;
        }
        
        $results = array();
        while($row = $stmt->fetch_assoc()) {
            $results[] = $row;
        }
        $stmt->close();
        
        return $results;
    }
    
    /**
     * Prepare a statement
     * @param string $sql
     * @param array $params
     * @return \Virge\Database\Component\Connection\Mysql\Statement
     * @throws InvalidQueryException
     */
    public function prepare($sql, $params = array()) {
        $stmt = $this->_resource->prepare($sql);
        if(!$stmt) {
            throw new InvalidQueryException(sprintf("Failed to execute query %s, error: %s", $sql, $this->_resource->error));
        }
        if(count($params) > 0) {
            $bindTypes = array();
            $bindValues = array();
            
            foreach($params as $key => $paramValue) {
                $bindTypes[] = $this->getValueType($paramValue);
                if($paramValue instanceof \DateTime) {
                    $paramValue = $paramValue->format('Y-m-d H:i:s');
                }
                
                if(is_object($paramValue)){
                    $paramValue = (string) $paramValue;
                }
                
                $varName = 'var' . $key;
                $$varName = $paramValue;
                $bindValues[] = &$$varName;
            }

            array_unshift($bindValues, implode('', $bindTypes));

            call_user_func_array(array($stmt, 'bind_param'), $bindValues);
        }
        return $stmt;
    }
    
    /**
     * Get param type based on value
     * @param mixed $value
     * @return string
     */
    public function getValueType($value) {
        if(is_string($value)){
            return $this->getParamType('varchar');
        }
        
        if($value instanceof \DateTime) {
            return $this->getParamType('timestamp');
        }
        
        if(is_int($value)){
            return $this->getParamType('int');
        }
        
        if(is_double($value)){
            return $this->getParamType('double');
        }
        
        if(is_float($value)){
            return $this->getParamType('decimal');
        }
        
        return $this->getParamType('varchar'); 
    }
    
    /**
     * Return the parameter type base on given string
     * @param string $type
     * @return string
     */
    public function getParamType($type){

        if(strstr($type, 'varchar') !== false){
            return 's';
        }

        if(strstr($type, 'timestamp') !== false){
            return 's';
        }

        if(strstr($type, 'date') !== false){
            return 's';
        }

        if(strstr($type, 'datetime') !== false){
            return 's';
        }

        if(strstr($type, 'enum') !== false){
            return 's';
        }

        if(strstr($type, 'char') !== false){
            return 's';
        }

        if(strstr($type, 'int') !== false){
            return 'i';
        }

        if(strstr($type, 'double') !== false){
            return 'd';
        }

        if(strstr($type, 'decimal') !== false){
            return 'd';
        }

        if(strstr($type, 'text') !== false){
            return 's';
        }
        
        return 's';
    }
}