<?php

namespace Strategy;

class Neutrality implements StrategyInterface {

	protected $type = 'neutrality';

	function getConfig() {
	    return array(
	        'type' => $this->type,
	    );
	}
	
	function isConfigured() {
	    return true;
	}
	
}