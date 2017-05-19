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
class CommitCommand extends Command 
{
    const COMMAND = 'virge:db:schema:commit';
    const COMMAND_HELP = 'Commit any un-run database schema migrations';
    const COMMAND_USAGE = 'virge:db:schema:commit';
    
    /**
     * Create a migration
     */
    public function run(Input $input) 
    {
        $dir = Config::get('app_path') . 'db/';
        
        $this->getSchemaService()->commitMigrations($dir);
    }
    
    /**
     * @return SchemaService
     */
    protected function getSchemaService() {
        return Virge::service('virge/schema');
    }
}
