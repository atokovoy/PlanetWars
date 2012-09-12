<?php

namespace Strategy;

class Half implements StrategyInterface {


	protected $type = 'half';
	
	public $configured = true;
	
	function getConfig() {
	    return array(
	        'type' => $this->type,
	    );
	}
	
	function isConfigured() {
	    return true;
	}
}