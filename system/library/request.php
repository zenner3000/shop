<?php
/*
HTTP请求类
包含了POST,GET ,COOKIE请求,服务器信息等信息
*/
class Request {
	public $get = array();
	public $post = array();
	public $cookie = array();
	public $files = array();
	public $server = array();

	public function __construct() {
		$this->get = $this->clean($_GET);
	//	print_r($this->get);
		$this->post = $this->clean($_POST);
		$this->request = $this->clean($_REQUEST);
		$this->cookie = $this->clean($_COOKIE);
	//	print_r($this->cookie);
		$this->files = $this->clean($_FILES);
	//	print_r($this->files);
		$this->server = $this->clean($_SERVER);
	//	print_r($this->server);
	}

	public function clean($data) {
		if (is_array($data)) {
			foreach ($data as $key => $value) {
				unset($data[$key]);

				$data[$this->clean($key)] = $this->clean($value);
			}
		} else {
			$data = htmlspecialchars($data, ENT_COMPAT, 'UTF-8');
		}

		return $data;
	}
}