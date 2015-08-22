<?php
use Virge\Database\Component\Schema;
/**
 * Setup schema migration database tables (using the schema migration tool)
 * @author Michael Kramer
 */

Schema::create(function() {
    Schema::table('virge_migration');
    Schema::id('id');
    Schema::string('filename')->setIndex('INDEX');
    Schema::timestamp('executed_on');
    Schema::string('executed_by');
    Schema::text('summary'); //all output
    Schema::end();
});