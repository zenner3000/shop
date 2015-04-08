<?php

	function loadtpl() {
 

		$file = 'D:\wamp\www\shop\test.tpl';

		if (file_exists($file)) {
			

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

	echo loadtpl();

 