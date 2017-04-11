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
class InitCommand extends Command 
{
    const COMMAND = 'virge:db:schema:init';
    const COMMAND_HELP = 'Setup the required database tables to track database migrations';
    const COMMAND_USAGE = 'virge:db:schema:init';
    
    /**
     * Create a migration
     */
    public function run(Input $input) 
    {
        include_once Config::path("Virge\\Database@resources/setup/database.php");
        Cli::success('Successfully initialized migration table');
    }
    
    /**
     * @return SchemaService
     */
    protected function getSchemaService() {
        return Virge::service('virge/schema');
    }
}
