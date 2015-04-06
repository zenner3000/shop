<?php


class  God{
	public $member1 = 1;
	public $member2;

	public function printline(){
		echo '\n';
	}
}

abstract class Controller1{
	public $registry;

	public function __construct($registry) {
		$this->registry = $registry;
	}

	public function __get($key) {
		return $this->registry->get($key);
	}

	public function __set($key, $value) {
		$this->registry->set($key, $value);
	}
}

final class Test extends Controller1{	
 	
    

	public function __construct(){
		 
	}
 	
 	public function outtest(){
 		echo $this->member1;	
 	}
}

$test = new Test(new God());

$test->outtest();