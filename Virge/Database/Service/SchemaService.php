<?php
namespace Virge\Database\Service;

use Virge\Core\Config;
use Virge\Database;
use Virge\Database\Component\Schema;

use Virge\Virge;

/**
 * 
 * @author Michael Kramer
 */
class SchemaService {
    
    /**
     * Commit our migrations, pass in a directory will read all files within
     * and commit them as migrations
     * @param string $dir
     */
    public function commitMigrations($dir) {
        $fileArray = Virge::dirToArray($dir);
        
        //TODO: use the Virge ORM
        $sql = "SELECT DISTINCT(`filename`) AS `file` FROM `virge_migration`";
        $result = Database::query($sql);
        $existing = array();
        foreach($result as $row) {
            $existing[] = $row['file'];
        }
        $files = $fileArray['file'];
        uasort($files, function($a, $b) use($dir) {

            $aFilename = pathinfo($a, PATHINFO_FILENAME);
            $aDateTime = \DateTime::createFromFormat('Y-m-d-H-i-s', substr($aFilename, strlen($aFilename) - 19));

            $bFilename = pathinfo($b, PATHINFO_FILENAME);
            $bDateTime = \DateTime::createFromFormat('Y-m-d-H-i-s', substr($bFilename, strlen($bFilename) - 19));

            if($aDateTime < $bDateTime) {
                return -1;
            }

            if($aDateTime > $bDateTime) {
                return 1;
            }

            if (filemtime($dir . $a) < filemtime($dir . $b)) {
                return 1;
            }
            return -1;
        });

        foreach ($files as $file) {
            if (!in_array($file, $existing)) {
                include $dir . $file;
                $this->logMigration($file);
            }
        }

        Schema::finish();
    }
    
    /**
     * Create the migration
     * @param string $table
     * @return string|boolean
     */
    public function createMigration($table) {
        $filename = str_replace(' ', '_', $table) . date('Y-m-d-H-i-s') . '.php';
        $file = Config::get('app_path') . 'db/' . $filename;

        
        $result = file_put_contents($file, file_get_contents(Config::path("Virge\\Database@resources/stubs/install.php")));
        
        return $result ? $file : false;
    }
    
    /**
     * Log that the migration happened
     * TODO: use the virge ORM
     * @param string $file
     */
    public function logMigration($file) {
        $user = get_current_user();
        $sql = "INSERT INTO `virge_migration` (`filename`, `executed_by`, `executed_on`, `summary`) VALUES (?, ?, ?, ?)";
        Database::query($sql, array($file, $user, new \DateTime(), Schema::$last_response));
    }
}