<?php

namespace MCUCourseCLI\Command;

use Illuminate\Console\Command as BaseCommand;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\DomCrawler\Crawler;

use MCUCourseCLI\Config;
use MCUCourseCLI\Parser\CourseParser;

class CourseCommand extends BaseCommand {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'course';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Fetch course data form course system.';

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
    $progressHelper = $this->getHelperSet()->get('progress');

    $columnFunction = function(Crawler $node, $index){
      $parsedData = &$this->lastParsedData;
      $nodeData = $node->text();
      switch($index) {
        case 0:
          $parsedData['system'] = $this->getSystemCode($nodeData);
          break;
        case 1:
          $courseCodeAndName = $this->splitCourseCodeAndName($nodeData);
          $parsedData['course_code'] = $courseCodeAndName[0];
          $parsedData['course_name'] = $courseCodeAndName[1];
          break;
        case 2:
          $parsedData['class_code'] = trim($nodeData);
          break;
        case 3:
          $peopleStatus = $this->getPeopleStatus($nodeData);
          $parsedData['max_people'] = $peopleStatus[0];
          $parsedData['selected_people'] = $peopleStatus[1];
          break;
        case 4:
          $parsedData['teacher'] = $this->getTeacher($node);
          break;
        case 5:
          $parsedData['time'] = $this->getTime($nodeData);
          var_dump($parsedData['time']);
          break;
      }
    };

    $rowFunction = function(Crawler $node, $index) use ($progressHelper) { // Notice: $this will refere to CourseParser instance
      if($index == 0) return; // Skip table header
      $progressHelper->advance();
      $node->filter('td')->each($this->columnFunction);
      $this->courseData[] = $this->lastParsedData;
      $this->lastParsedData = array();
    };

    $this->info('開始分析課程資料⋯⋯');
    $parser = new CourseParser($rowFunction, $columnFunction);
    $progressHelper->start($this->output, $parser->count());
    $courseData = $parser->parse();
    $progressHelper->finish();
    $this->info('課程資料分析完成');
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			//array('example', InputArgument::REQUIRED, 'An example argument.'),
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
			//array('example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null),
		);
	}

}
