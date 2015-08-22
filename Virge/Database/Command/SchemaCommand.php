<?php
namespace Virge\Database\Command;

use Virge\Cli;
use Virge\Cli\Component\Command;
use Virge\Core\Config;
use Virge\Database\Service\SchemaService;

use Virge\Virge;

/**
 * 
 * @author Michael Kramer
 */
class SchemaCommand extends Command {
    
    /**
     * Create a migration
     * @param string $table
     */
    public function create($table) {
        
        $result = $this->getSchemaService()->createMigration($table);
        if($result) {
            Cli::output("Successfully created file {$result}");
        } else {
            Cli::output("Failed to create migration for {$table}");
        }
    }
    
    /**
     * Commit any pending migrations
     */
    public function commit() {
        $dir = Config::get('app_path') . 'db/';
        
        $this->getSchemaService()->commitMigrations($dir);
    }
    
    /**
     * Setup table to hold our migrations
     */
    public function init() {
        include_once Config::path("Virge\\Database@resources/setup/database.php");
        Cli::output('Successfully initialized migration table');
    }
    
    /**
     * @return SchemaService
     */
    protected function getSchemaService() {
        return Virge::service('virge/schema');
    }
}