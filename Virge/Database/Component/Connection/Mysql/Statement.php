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

        return $this->params = $args;
    }

    public function execute($params = null)
    {
        $params = array_combine(array_keys($params ?? $this->getParams()), array_map(function($param){
            if($param instanceof \DateTime) {
                return $param->format("Y-m-d H:i:s");
            }

            if(is_object($param)) {
                return (string) $param;
            }
                
            return $param;
        }, $params ?? $this->getParams()));

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