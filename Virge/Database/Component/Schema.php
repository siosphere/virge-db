<?php
namespace Virge\Database\Component;

use Virge\Cli;
use Virge\Database\Component\Schema\Drop;
use Virge\Database\Component\Schema\Rename;
use Virge\Database\Component\Schema\Field;
use Virge\Database\Component\Schema\Field\Id;
use Virge\Database\Component\Schema\Index;
use Virge\Database\Component\Schema\Reference;
use Virge\Database;

/**
 * 
 */
class Schema{
    
    protected static $table_name = NULL;
    
    protected static $fields = array();
    
    protected static $drop = array();
    
    protected static $renames = array();
    
    protected static $index = array();
    
    protected static $references = array();
    
    public static $errors = array();
    
    public static $error = '';
    
    public static $model = false;
    
    public static $alter = false;
    
    public static $last_response = '';
    
    public static $connection = 'default';
    
    /**
     * Create our schema
     * @param type $callable
     */
    public static function create($callable, $connection = 'default') {
        self::$connection = $connection;
        call_user_func($callable);
    }
    
    /**
     * Start a table
     * @param string $table_name
     */
    public static function table($table_name){
        self::$table_name = $table_name;
    }
    
    /**
     * Alter a table
     * @param string $table_name
     */
    public static function alter($table_name){
        self::$table_name = $table_name;
        self::$alter = true;
    }
    
    /**
     * Drop a column
     * @param string $field_name
     * @return type
     */
    public static function dropColumn($field_name){
        return self::$drop[] = new Drop(array(
            'field_name'    =>      $field_name,
        ));
    }
    
    /**
     * Rename a column
     * @param string $field_name
     * @param string $new_field_name
     * @param string $data_type
     * @return Rename
     */
    public static function renameColumn($field_name, $new_field_name, $data_type){
        return self::$renames[] = new Rename(array(
            'field_name'        =>      $field_name,
            'new_field_name'    =>      $new_field_name,
            'data_type'         =>      $data_type,
        ));
    }
    
    /**
     * Create a primary ID column
     * @param string $name
     * @param array $params
     * @return Id
     */
    public static function id($name, $params = array()){
        $params['name'] = $name;
        return self::$fields[] = new Id($params);
    }
    
    /**
     * Create a new text column
     * @param string $name
     * @param array $params
     * @return Field
     */
    public static function text($name, $params = array()){
        $params['name'] = $name;
        $params['type'] = 'TEXT';
        return self::$fields[] = new Field($params);
    }
    
    /**
     * Create new string column
     * @param string $name
     * @param array $params
     * @return Field
     */
    public static function string($name, $params = array()){
        $params['name'] = $name;
        $params['type'] = 'VARCHAR';
        if(!isset($params['length'])){
            $params['length'] = 255;
        }
        return self::$fields[] = new Field($params);
    }
    
    /**
     * Create new integer column
     * @param string $name
     * @param array $params
     * @return Field
     */
    public static function int($name, $params = array()){
        $params['name'] = $name;
        $params['type'] = 'INT';
        return self::$fields[] = new Field($params);
    }
    
    /**
     * Create new double column
     * @param string $name
     * @param array $params
     * @return Field
     */
    public static function double($name, $params = array()){
        $params['name'] = $name;
        $params['type'] = 'DOUBLE';
        return self::$fields[] = new Field($params);
    }
    
    /**
     * Create new float column
     * @param string $name
     * @param array $params
     * @return Field
     */
    public static function float($name, $params = array()){
        $params['name'] = $name;
        $params['type'] = 'FLOAT';
        return self::$fields[] = new Field($params);
    }
    
    /**
     * Create new string bool
     * @param string $name
     * @param array $params
     * @return Field
     */
    public static function bool($name, $params = array()){
        $params['name'] = $name;
        $params['type'] = 'TINYINT';
        $params['length'] = '1';
        return self::$fields[] = new Field($params);
    }
    
    /**
     * Create new timestamp column
     * @param string $name
     * @param array $params
     * @return Field
     */
    public static function timestamp($name, $params = array()){
        $params['name'] = $name;
        $params['type'] = 'TIMESTAMP';
        return self::$fields[] = new Field($params);
    }
    
    /**
     * Create new date column
     * @param string $name
     * @param array $params
     * @return Field
     */
    public static function date($name, $params= array()){
        $params['name'] = $name;
        $params['type'] = 'DATE';
        return self::$fields[] = new Field($params);
    }
    
    /**
     * Create new time column
     * @param string $name
     * @param array $params
     * @return Field
     */
    public static function time($name, $params= array()){
        $params['name'] = $name;
        $params['type'] = 'TIME';
        return self::$fields[] = new Field($params);
    }
    
    /**
     * Create new datetime column
     * @param string $name
     * @param array $params
     * @return Field
     */
    public static function datetime($name, $params= array()){
        $params['name'] = $name;
        $params['type'] = 'DATETIME';
        return self::$fields[] = new Field($params);
    }
    
    /**
     * Create new index
     * @param string $name
     * @param array $columns
     * @param array $params
     * @return Index
     */
    public static function index($name, $columns = array(), $params = array()){
        self::$index[] = new Index(array(
            'table' => self::$table_name,
            'name' => $name,
            'columns' => $columns,
            'type' => 'KEY'
        ));
    }
    
    /**
     * Create a unique index
     * @param string $name
     * @param array $columns
     * @param array $params
     * @return Index
     */
    public static function unique($name, $columns = array(), $params = array()){
        self::$index[] = new Index(array(
            'table' => self::$table_name,
            'name' => $name,
            'columns' => $columns,
            'type' => 'UNIQUE'
        ));
    }
    
    /**
     * Create a foreign key
     * $field_name - column in current table to map
     * $table_field - table_name/column_name to map to
     * @param string $field_name
     * @param string $table_field
     * @param array $params
     */
    public static function reference($field_name, $table_field, $params = array()){
        $params['field'] = $field_name;
        $table = self::$table_name;
        $params['table'] = $table;
        $data = explode('/', $table_field);
        list($reference_table, $reference_field) = $data;
        $params['reference_table'] = $reference_table;
        $params['reference_field'] = $reference_field;
        self::$references[] = new Reference($params);
    }
    
    /**
     * End the current schema change
     * @return boolean
     */
    public static function end(){
        $table_name = self::$table_name;
        if(!$table_name){
            return false;
        }
        
        if(self::$model && !self::$alter){
            self::setupCommon();
        }
        
        $auto = false;
        if(self::$alter){
            Cli::output('Altering ' . $table_name);
            $sql = "ALTER TABLE `{$table_name}` ";
        } else {
            Cli::output('Creating ' . $table_name);
            $sql = "CREATE TABLE IF NOT EXISTS `{$table_name}` ( ";
        }
        $i = 0;
        foreach(self::$fields as $field){
            if($i > 0){
                $sql .= ', ';
            }
            if(self::$alter){
                $sql .= "ADD COLUMN ";
            }
            $sql .= $field->getQuery(self::$alter);
            $i++;
            if($field->getIncrement()){
                $auto = true;
            }
        }
        
        //keys
        foreach(self::$fields as $field){
            if($field->getIndex()){
                if(!self::$alter) {
                    $sql .= ", " . $field->getIndexQuery();
                } else {
                    self::index($field->getName(), [$field->getName()]);
                }
            }
        }
        if(!self::$alter){
            $sql .= ") ENGINE=InnoDB DEFAULT CHARSET=latin1";
            
            if($auto){
                $sql .= " AUTO_INCREMENT=1";
            }
        }
        
        foreach(self::$renames as $rename){
            if($i > 0){
                $sql .= ', ';
            }
            $sql .= $rename->getQuery();
            $i++;
        }
        
        $sql .= ";";
        
        $result = Database::connection(self::$connection)->query($sql);
        if(!$result){
            self::$last_response = Database::connection(self::$connection)->getError();
            
            if(self::$alter){
                self::$error = 'Failed to altered ' . self::$table_name . ': ' . self::$last_response;
            } else {
                self::$error = 'Failed to create ' . self::$table_name . ': ' . self::$last_response;
            }
            
            Cli::output(self::$error . ': ' . self::$last_response);
            self::reset();
            
            return false;
        } else {
            if(self::$alter){
                self::$last_response = 'Successfully altered ' . self::$table_name;
            } else {
                self::$last_response = 'Successfully created ' . self::$table_name;
            }

            Cli::output(self::$last_response);
        }
        
        self::reset();
        return $result;
    }
    
    /**
     * Setup common fields for use with database persistence
     * 
     */
    public static function setupCommon() {
        self::int('created_by', array('length' => 11))->setIndex('INDEX');
        self::timestamp('created_on');
        self::int('last_modified_by', array('length' => 11))->setIndex('INDEX');
        self::timestamp('last_modified_on');
        self::bool('deleted');
        self::int('deleted_by', array('length' => 11))->setIndex('INDEX');
        self::timestamp('deleted_on');

        //references
        self::reference('created_by', 'user/id');
        self::reference('last_modified_by', 'user/id');
        self::reference('deleted_by', 'user/id');
    }
    
    /**
     * Finish out our schema change by adding any indexes, and foreign keys
     */
    public static function finish()
    {
        foreach(self::$index as $index) {
            Database::connection(self::$connection)->query($index->getQuery());
        }

        $database = Database::connection(self::$connection)->getDatabase();

        foreach(self::$references as $reference){
            Database::connection(self::$connection)->query($reference->getQuery($database));
        }
    }
    
    /**
     * Reset for the next schema change
     */
    public static function reset(){
        self::$model = false;
        self::$table_name = NULL;
        self::$fields = array();
        self::$model = false;
        //self::$references = array();
        self::$renames = array();
        self::$alter = false;
        if(!empty(self::$last_response)){
            self::$errors[] = self::$last_response;
        }
    }
}