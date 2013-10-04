<?php

namespace MCUCourseCLI\Command;

use Illuminate\Console\Command as BaseCommand;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

use Illuminate\Database\Migrations\Migrator;
use Illuminate\Database\Migrations\DatabaseMigrationRepository;
use Illuminate\Filesystem\Filesystem;

use MCUCourseCLI\Config;
use MCUCourseCLI\Database;

class MigrateCommand extends BaseCommand {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'migrate';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Run database migrations';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return void
	 */
	public function fire()
	{
    $config = Config::getInstance();
    $database = Database::getInstance();

    $resolver = $database->getResolver();
    $repository = new DatabaseMigrationRepository($resolver, $config->get('migrationTable'));
    if(!$repository->repositoryExists()) { // Create repository when migrations table not exists
      $repository->createRepository();
    }

    $filesystem = new Filesystem;

    $migrator = new Migrator($repository, $resolver, $filesystem);
    $migrations = $config->getCommandPath() . '/../migrations';

    $rollback = $this->argument('rollback');

    switch(true) {
      case $rollback:
        $migrator->rollback();
        break;
      default:
       $migrator->run($migrations);
    }

    foreach($migrator->getNotes() as $note) {
      $this->output->writeln($note);
    }
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
      array('rollback', InputArgument::OPTIONAL, 'Rollback to last database migration', false)
		);
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array(
		);
	}

}
