<?php

namespace Strategy;

class Player implements StrategyInterface {

	protected $type = 'player';
	
	function getConfig() {
	    return array(
	        'type' => $this->type,
	    );
	}
	
	function isConfigured() {
	    return false;
	}
}