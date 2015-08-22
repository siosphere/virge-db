<?php
namespace Virge\Database\Component\Connection\Mysql;

/**
 * 
 * @author Michael Kramer
 */

class Statement extends \mysqli_stmt {
    protected $varsBound = false;
    protected $results;

    /**
     * @return array
     */
    public function fetch_assoc()
    {
        if(!$this->store_result()){
            //echo $this->error;
        }
        
        $bindVarArray = array();
        // checks to see if the variables have been bound, this is so that when
        //  using a while ($row = $this->stmt->fetch_assoc()) loop the following
        // code is only executed the first time
        if (!$this->varsBound) {
            $meta = $this->result_metadata();
            if(!$meta){
                return false; //nothing to return
            }
            while ($column = $meta->fetch_field()) {
                
                // this is to stop a syntax error if a column name has a space in
                // e.g. "This Column". 'Typer85 at gmail dot com' pointed this out
                $columnName = str_replace(' ', '_', $column->name);
                
                $bindVarArray[] = &$this->results[$columnName];
            }
            call_user_func_array(array($this, 'bind_result'), $bindVarArray);
            $this->varsBound = true;
        }
        
        $i=0;
        if($this->fetch() != null)
        {
            $array[$i] = array();
            foreach($this->results as $k=>$v)
                $array[$k] = $v;
            $i++;
            
            return $array;
        }
        
        return null;
        if ($this->fetch() != null) {
            foreach ($this->results as $k => $v) {
                $results[$k] = $v;
            }
            return $results;
        } else {
            return null;
        }
    }
}