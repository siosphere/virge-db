<?php

use Virge\Cli;

/**
 * Registers all given handlers with Virge that this Capsule contains
 * @author Michael Kramer
 */

Cli::add('db:schema:create', '\\Virge\\Database\\Command\\SchemaCommand', 'create');
Cli::add('db:schema:commit', '\\Virge\\Database\\Command\\SchemaCommand', 'commit');
Cli::add('db:schema:init', '\\Virge\\Database\\Command\\SchemaCommand', 'init');