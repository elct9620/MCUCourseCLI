<?php

namespace MCUCourseCLI\Command;

use Illuminate\Console\Command as BaseCommand;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

use MCUCourseCLI\Parser\DepartmentParser;

class InitCommand extends BaseCommand {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'init';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Initialize MCUCourseCLI for API Query.';

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
    $parser = new DepartmentParser();
    $data = $parser->parse();

    $this->comment("All departments:");
    foreach($data["departments"] as $code => $department) {
      $this->info("[{$code}] {$department}");
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
