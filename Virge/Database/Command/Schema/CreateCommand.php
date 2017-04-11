<?php
namespace Virge\Database\Command\Schema;

use Virge\Cli;
use Virge\Cli\Component\{
    Command,
    Input
};
use Virge\Core\Config;
use Virge\Database\Service\SchemaService;

use Virge\Virge;

/**
 * 
 * @author Michael Kramer
 */
class CreateCommand extends Command 
{
    const COMMAND = 'virge:db:schema:create';
    const COMMAND_HELP = 'Create database schema migrations';
    const COMMAND_USAGE = 'virge:db:schema:create table_name';
    
    /**
     * Create a migration
     */
    public function run(Input $input) 
    {
        $table = $input->getArgument(0);

        $result = $this->getSchemaService()->createMigration($table);
        if($result) {
            Cli::success("Successfully created file {$result}");
        } else {
            Cli::error("Failed to create migration for {$table}");
            $this->terminate(-1);
        }
    }
    
    /**
     * @return SchemaService
     */
    protected function getSchemaService() {
        return Virge::service('virge/schema');
    }
}
