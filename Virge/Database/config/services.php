<?php
use Virge\Virge;
/**
 * 
 * @author Michael Kramer
 */

Virge::registerService('virge/database', '\\Virge\\Database\\Service\\DatabaseService');

Virge::registerService('virge/schema', '\\Virge\\Database\\Service\\SchemaService');