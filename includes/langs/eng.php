<?php

	 function lang($phrase){

		static $lang = array(

			'Message' => 'Welcome',
			'Admin'   => 'administrator'

			);

		return $lang[$phrase];
	}

?>