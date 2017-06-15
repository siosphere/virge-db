<?php
namespace Virge\Database\Component\Connection\Mysql;

/**
 * 
 * @author Michael Kramer
 */

class Statement
{
    protected $stmt;
    protected $params;

    public function __construct(\PDOStatement $stmt, array $params = [])
    {
        $this->stmt = $stmt;
        $this->params = $params;
    }

    public function getStmt() : \PDOStatement
    {
        return $this->stmt;
    }

    public function getParams()
    {
        return $this->params;
    }

    public function bind_param()
    {
        $args = func_get_args();
        array_shift($args);

        return $this->params = array_map(function($arg) {
            if($arg instanceof \DateTime) {
                return $arg->format("Y-m-d H:i:s");
            }
            
            return $arg;
        }, $args);
    }

    public function execute($params = null)
    {
        if($params) {
            $params = array_map(function($arg) {
                if($arg instanceof \DateTime) {
                    return $arg->format("Y-m-d H:i:s");
                }
                
                return $arg;
            }, $params);
        } else {
            $params = $this->getParams();
        }

        $result = $this->stmt->execute($params);
        $this->error = $this->error();
        return $result;
    }

    public function fetch_assoc()
    {
        return $this->stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function fetchAll()
    {
        return $this->stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function close()
    {
        return $this->stmt->closeCursor();
    }

    public function errorCode()
    {
        return $this->stmt->errorCode();
    }

    public function errorInfo() : array
    {
        return $this->stmt->errorInfo();
    }

    public function error()
    {
        $code = $this->errorCode();
        if(!$code || $code = '00000') {
            return false;
        }

        $info = $this->errorInfo();

        return sprintf("[%s] (%s): %s" . $info[0], $info[1], $info[2]);
    }
}