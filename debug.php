	<?php

	//获取调用堆栈
	function print_stack_trace()
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