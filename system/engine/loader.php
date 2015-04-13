<?php
final class Loader {
	private $registry;


	//获取调用堆栈
	public function print_stack_trace()
	{
	    $array =debug_backtrace();
	  //print_r($array);//信息很齐全
	   unset($array[0]);
	   foreach($array as $row)
	    {
	       $html .=$row['file'].':'.$row['line'].'行,调用方法:'.$row['function']."<p>";
	    }
	    return $html;
	}
	


	public function __construct($registry) {
		$this->registry = $registry;
	}

	//执行动作。调用响应的类里的方法
	public function controller($route, $args = array()) {
		$action = new Action($route, $args);

		return $action->execute($this->registry);
	}


	//找到响应的mode类，new一个，用$registry->set设置一下,实际是添加到$registry里面的data数组里
	public function model($model) {
		
	//	print_stack_trace();
		$file = DIR_APPLICATION . 'model/' . $model . '.php';
		$class = 'Model' . preg_replace('/[^a-zA-Z0-9]/', '', $model);

		if (file_exists($file)) {
			include_once($file);

			$this->registry->set('model_' . str_replace('/', '_', $model), new $class($this->registry));
		} else {
			trigger_error('Error: Could not load model ' . $file . '!');
			exit();
		}
	}


	//加载模板内容到缓冲区,返回该缓冲区变量
	public function view($template, $data = array()) {
	//	print_r($template);
	//	echo $data;
	//	print_stack_trace();

		$file = DIR_TEMPLATE . $template;

		if (file_exists($file)) {
			//把$data里面的成员添加到符号表（我理解为实际上就是 $变量= xx值 这样的形式）
			extract($data);  

			ob_start();

			require($file);

			$output = ob_get_contents();

			ob_end_clean();

			return $output;
		} else {
			trigger_error('Error: Could not load template ' . $file . '!');
			exit();
		}
	}


	public function library($library) {
		$file = DIR_SYSTEM . 'library/' . $library . '.php';

		if (file_exists($file)) {
			include_once($file);
		} else {
			trigger_error('Error: Could not load library ' . $file . '!');
			exit();
		}
	}

	public function helper($helper) {
		$file = DIR_SYSTEM . 'helper/' . $helper . '.php';

		if (file_exists($file)) {
			include_once($file);
		} else {
			trigger_error('Error: Could not load helper ' . $file . '!');
			exit();
		}
	}

	public function config($config) {
		$this->registry->get('config')->load($config);
	}

	public function language($language) {
		return $this->registry->get('language')->load($language);
	}
}