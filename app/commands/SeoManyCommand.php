<?php
namespace Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;



class SeoManyCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'command:seomany';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Generates seo stuff';
	protected $writer;	

	public function __construct($writer)
	{

		parent::__construct();
		$this->writer = $writer;
	}

	/**
	 * Execute the console command.
	 *
	 * @return void
	 */
	public function fire()
	{
		$masterPath = app_path().'\views\layouts\master.blade.php';
		$master = \File::get($masterPath, 'r');

		//dd($master);
		$start = strpos($master, '<!-- AutoStart -->');
		$finish = strpos($master, '<!-- AutoFinish -->', $start);

		//dd($start . ' -- '. $finish);
		$masterP1 = substr($master, 0, $start); 
		$masterP2 = ''; 
		$masterP3 = substr($master, $finish);

		
		$m = substr($master, $start, $finish - $start);
		$s= 0;
		$f= 0;
		$i=0;
		while (true) {
		$s = strpos($m, '{{', $s);
		if ($s===false)
		break;
		$f = strpos($m, '}}', $s)+2;
		
		$l = substr($m, $s, $f - $s);

		$s1 = 0;
		$s1  = strpos($l, "'", $s1)+1;
		$s1  = strpos($l, "'", $s1)+1;
		$s1  = strpos($l, "'", $s1)+1;
		$f1  = strpos($l, "'", $s1);

		$keywords = substr($l, $s1, $f1 - $s1);
		$keywords = str_replace(" ", "-", ucwords(strtolower($keywords)));

		$controllerName = $this->addRoute($keywords);

		$viewPath = $this->addController($controllerName, $keywords);	
	 // 3. Create View with seo keywords
		$this->addView($viewPath, $keywords);	

		$linkNew = $this->addLink($keywords);
		$m = str_replace($l, $linkNew, $m);
		$s=$f;
		

		}

		dd($m);

	 // 1. Add route
		
	 // 2. Create Controller
	

		

		$this->info('+++++++ Seems no mistakes +++++++');	

	}

	protected function addLink($keywords)
	{
		$path = app_path().'\views\CopyLinks.txt';
		$linkTitle = ucwords(str_replace('-', ' ', $keywords));
		$controllerNameArray = explode("-", $keywords);
		$alias= ($controllerNameArray[0])."-" . ($controllerNameArray[1]);  
		$link_to_route = "{{link_to_route('$alias', '$linkTitle', array(), array('class'=>'localLinks'))}}";

		if (\File::put($path, $link_to_route))
			$this->info("Write $path was succesful");	
		else
			$this->error("Error writing $path");


		return $link_to_route;
		
	}

	protected function addView($viewPath, $keywords)
	{
		$viewTemplate = \File::get(__DIR__.'\ViewTemplate.txt');
		$viewTemplate = str_replace('{{keywords}}', $keywords, $viewTemplate);
		$h1 = str_replace('-', ' ', $keywords);

		$viewTemplate = str_replace('{{H1}}', $h1, $viewTemplate);
		$path = $viewPath;
		if (\File::put($path, $viewTemplate))
			$this->info("Write $path was succesful");	
		else
			$this->error("Error writing $path");	

		
	}


	protected function addController($controllerName, $keywords)
	{
		$path = app_path()."/controllers/seo/$controllerName.php";
		$url = $keywords;
		$title = str_replace('-', ', ', $keywords); 
		$meta = str_replace('-', ', ', $keywords).'ONE STOP Real Estate SERVICE'; 
		$arr_keywords = explode('-', $keywords);
		$viewName = "seo.vw_".strtolower($arr_keywords[0].'_'.$arr_keywords[1]);	
		$viewPath = app_path()."/views/seo/vw_".strtolower($arr_keywords[0].'_'.$arr_keywords[1]).'.blade.php';	

		$controllerTemplate = \File::get(__DIR__.'\ControllerTemplate.txt');

		$controllerTemplate = str_replace('{{url}}', $url, $controllerTemplate);
		$controllerTemplate = str_replace('{{controllerName}}', $controllerName, $controllerTemplate);
		$controllerTemplate = str_replace('{{title}}', $title, $controllerTemplate);
		$controllerTemplate = str_replace('{{meta}}', $meta, $controllerTemplate);
		$controllerTemplate = str_replace('{{viewName}}', $viewName, $controllerTemplate);

		if (strpos($keywords, 'Sale')!==false)
			$issale = 1;
		else
			$issale=0;


		if (strpos($keywords, 'skokie')!==false) {
			$city = 'skokie';
		} elseif (strpos($keywords, 'niles')!==false) {
			$city = 'niles';
		} elseif (strpos($keywords, 'evanston')!==false) {
			$city = 'evanston';
		} elseif (strpos($keywords, 'glenview')!==false) {
			$city = 'glenview';
		}elseif (strpos($keywords, 'Morton')!==false) {
			$city = 'Morton';
		
		
		$controllerTemplate = str_replace('{{issale}}', $issale, $controllerTemplate);
		$controllerTemplate = str_replace('{{city}}', $city, $controllerTemplate);


		if (\File::put($path, $controllerTemplate))
			$this->info("Write $path was succesful");	
		else
			$this->error("Error writing $path");	

		return $viewPath;




	}	


	protected function addRoute($keywords)
	{

		$controllerNameArray = explode("-", $keywords);

		$controllerName= ucwords($controllerNameArray[0]) . ucwords($controllerNameArray[1])."Controller";  
		$alias= ($controllerNameArray[0])."-" . ($controllerNameArray[1]);  
		
		$routeLine = "Route::get('$keywords', array('as'=>'$alias', 'uses'=>'$controllerName@index'));";
	//$this->info($routeLine);


	# code...
		$path = app_path().'/routes.php';
		if ($this->writer->put($path, $routeLine))
			$this->info("Write $path was succesful");	
		else
			$this->error("Error writing $path");	
		

		return $controllerName;

	}

	protected function getArguments()
	{
		return array(
			array('keywords', InputArgument::REQUIRED, 'seo important keywords'),
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
	//		array('example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null),
			);
	}

}