<?php

namespace MCUCourseCLI\Command;

use Illuminate\Console\Command as BaseCommand;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

use MCUCourseCLI\Config;
use MCUCourseCLI\Parser\DepartmentParser;
use MCUCourseCLI\Model\Department;

class DepartmentCommand extends BaseCommand {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'department';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Fetch department data from course system.';

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
    $departmentData = $parser->parseDepartment();
    $dataCount = count($departmentData);

    $this->info("開始將資料寫入資料庫");
    $progress = $this->getHelperSet()->get('progress');
    $progress->start($this->output, $dataCount);

    $notUpated = 0;

    foreach($departmentData as $code => $department) {
      $data = Department::where('code', '=', $code)->first();
      if($data) { // Skip exists data
        $notUpated = $notUpated + 1;
        $progress->advance();
        continue;
      }

      Department::create(array(
        "code" => $code,
        "name" => $department
      ));
      $progress->advance();
    }
    $progress->finish();

    $this->info("一共 {$dataCount} 筆資料，有 {$notUpated} 資料未更新。");
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			// array('example', InputArgument::REQUIRED, 'An example argument.'),
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
			// array('example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null),
		);
	}

}
