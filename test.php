<?php

final class Test{
	
	public $count = 0;

	public function __construct(){
		$this->output();
	}

	public function output(){
		echo $this->$count;
	}
}

$test = new Test();