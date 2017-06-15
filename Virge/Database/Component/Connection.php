<?php
namespace Virge\Database\Component;

use Virge\Core\Config;

/**
 * 
 * @author Michael Kramer
 */
abstract class Connection {
    
    /**
     * Holds the database resource
     * @var mixed 
     */
    protected $_resource;
    
    /**
     * Holds our last error
     * @var string
     */
    protected $error;
    
    protected $name;
    
    protected $hostname;
    
    protected $username;
    
    protected $password;
    
    protected $database;
    
    protected $port;
    
    protected $socket;

    protected $parameters;
    
    public abstract function connect();
    
    public abstract function query($sql, $params = array());
    
    public abstract function prepare($sql, $params = array());

    public abstract function ping();

    public abstract function insertId();

    public abstract function beginTransaction();

    public abstract function commit();

    public abstract function rollBack();
    
    /**
     * Returns a database resource
     * @return resource
     */
    public function getResource() {
        return $this->_resource;
    }
    
    /**
     * 
     * @param string $hostname
     */
    public function setHostname($hostname) {
        $this->hostname = $hostname;
    }
    
    /**
     * 
     * @param string $username
     */
    public function setUsername($username) {
        $this->username = $username;
    }
    
    /**
     * 
     * @param string $password
     */
    public function setPassword($password) {
        $this->password = $password;
    }
    
    /**
     * 
     * @param string $parameters
     */
    public function setAdditionalParameters($parameters) {
        $this->parameters = $parameters;
    }
    
    /**
     * 
     * @return string
     */
    public function getError() {
        return $this->error;
    }
    
    /**
     * 
     * @param string $error
     */
    public function setError($error){
        $this->error = $error;
    }
    
    protected function getConfigValue($key) {
        //$config = Config::get('database')['connections'][$this->getName()];
    }
    
    /**
     * 
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * 
     * @return string
     */
    public function getPort() {
        return $this->port;
    }

    /**
     * 
     * @return string
     */
    public function getSocket() {
        return $this->socket;
    }

    /**
     * 
     * @param string $name
     * @return \Virge\Database\Component\Connection
     */
    public function setName($name) {
        $this->name = $name;
        return $this;
    }

    public function setParameters(array $parameters) 
    {
        $this->parameters = $parameters;

        return $this;
    }

    public function getParameters()
    {
        return $this->parameters ?? [];
    }

    /**
     * 
     * @param string $port
     * @return \Virge\Database\Component\Connection
     */
    public function setPort($port) {
        $this->port = $port;
        return $this;
    }

    /**
     * 
     * @param string $socket
     * @return \Virge\Database\Component\Connection
     */
    public function setSocket($socket) {
        $this->socket = $socket;
        return $this;
    }
    
    /**
     * 
     * @return string
     */
    public function getHostname() {
        return $this->hostname;
    }

    /**
     * 
     * @return string
     */
    public function getUsername() {
        return $this->username;
    }

    /**
     * 
     * @return string
     */
    public function getPassword() {
        return $this->password;
    }
    
    /**
     * 
     * @return string
     */
    public function getDatabase() {
        return $this->database;
    }

    /**
     * 
     * @param string $database
     * @return \Virge\Database\Component\Connection
     */
    public function setDatabase($database) {
        $this->database = $database;
        return $this;
    }



}