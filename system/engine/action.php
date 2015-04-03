<?php
final class Action {
	private $file;
	private $class;
	private $method;
	private $args = array();

	public function __construct($route, $args = array()) {
		$path = '';

		// Break apart the route
		$parts = explode('/', str_replace('../', '', (string)$route));

		foreach ($parts as $part) {
			$path .= $part;

			if (is_dir(DIR_APPLICATION . 'controller/' . $path)) {
				$path .= '/';

				array_shift($parts);

				continue;
			}

			$file = DIR_APPLICATION . 'controller/' . str_replace(array('../', '..\\', '..'), '', $path) . '.php';

			if (is_file($file)) {
				$this->file = $file;

				$this->class = 'Controller' . preg_replace('/[^a-zA-Z0-9]/', '', $path);

				array_shift($parts);

				break;
			}
		}

		if ($args) {
			$this->args = $args;
		}

		$method = array_shift($parts);

		if ($method) {
			$this->method = $method;
		} else {
			$this->method = 'index';
		}
	}


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

	//public $count =1;
	//执行方法
	public function execute($registry) {

		// Stop any magical methods being called ，任何__开头的方法都不要执行
		if (substr($this->method, 0, 2) == '__') {
			return false;
		}

		if (is_file($this->file)) {
			include_once($this->file);

			$class = $this->class;
//	$this->$count++;
		//	echo $this->$count;
		//	print_stack_trace();
		//	print_r($class . "<br>");
			$controller = new $class($registry);

			//调用前先判断是否能调用
			if (is_callable(array($controller, $this->method))) {
				return call_user_func(array($controller, $this->method), $this->args);
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
}