<?php
/*
	响应类，发送回浏览器客户端的
包含了 重定向，输出到浏览器等等功能
*/
 
class Response {
	private $headers = array();
	private $level = 0;
	private $output;

	public function __construct(){
		// Configuration
		if (is_file('config.php')) {
			require_once('config.php');
		}
	}

	public function addHeader($header) {
		$this->headers[] = $header;
	}

	public function redirect($url, $status = 302) {
		header('Location: ' . str_replace(array('&amp;', "\n", "\r"), array('&', '', ''), $url), true, $status);
		exit();
	}

	public function setCompression($level) {
		$this->level = $level;
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
	

	public function setOutput($output) {
	//	print_stack_trace();
		$this->output = $output;
	}

	public function getOutput() {
		return $this->output;
	}

	//gzip，x-gzip压缩 
	private function compress($data, $level = 0) {
		if (isset($_SERVER['HTTP_ACCEPT_ENCODING']) && (strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') !== false)) {
			$encoding = 'gzip';
		}

		if (isset($_SERVER['HTTP_ACCEPT_ENCODING']) && (strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'x-gzip') !== false)) {
			$encoding = 'x-gzip';
		}

		if (!isset($encoding) || ($level < -1 || $level > 9)) {
			return $data;
		}

		if (!extension_loaded('zlib') || ini_get('zlib.output_compression')) {
			return $data;
		}

		if (headers_sent()) {
			return $data;
		}

		if (connection_status()) {
			return $data;
		}

		$this->addHeader('Content-Encoding: ' . $encoding);

		return gzencode($data, (int)$level);
	}


	//
	public function output() {
		if ($this->output) {
			if ($this->level) {
				$output = $this->compress($this->output, $this->level);
			} else {
				$output = $this->output;
			}

			//HTTP头是否已经发送，没有就发送
			if (!headers_sent()) {
				foreach ($this->headers as $header) {
					header($header, true);
				}
			}

			echo $output;
		}
	}
}