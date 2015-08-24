<?php

namespace Virge;

use Virge\Core\Config;

/**
 * 
 * @author Michael Kramer
 */
class Database {
    
    /**
     * Houses any of our connections
     * @var array 
     */
    protected static $_connections = array();
    
    protected static function getService() {
        //TODO: caching
        $serviceName = Config::get('database')['service'];
        return Virge::service($serviceName);
    }
    
    public static function __callStatic($name, $arguments) {
        return call_user_func_array(array(self::getService(), $name), $arguments);
    }
    
    public static function connection($name) {
        if(isset(self::$_connections[$name])) {
            return self::$_connections[$name];
        }
        
        //create new connection
        $connection = self::getService()->createConnection($name);
        
        return self::$_connections[$name] = $connection;
    }
}