<?php

use Virge\Cli;
use Virge\Database\Command\Schema\{
    CommitCommand,
    CreateCommand,
    InitCommand
};

/**
 * Registers all given handlers with Virge that this Capsule contains
 * @author Michael Kramer
 */

Cli::add(CommitCommand::COMMAND, CommitCommand::class)
    ->setHelpText(CommitCommand::COMMAND_HELP)
    ->setUsage(CommitCommand::COMMAND_USAGE)
;

Cli::add(CreateCommand::COMMAND, CreateCommand::class)
    ->setHelpText(CreateCommand::COMMAND_HELP)
    ->setUsage(CreateCommand::COMMAND_USAGE)
;

Cli::add(InitCommand::COMMAND, InitCommand::class)
    ->setHelpText(InitCommand::COMMAND_HELP)
    ->setUsage(InitCommand::COMMAND_USAGE)
;