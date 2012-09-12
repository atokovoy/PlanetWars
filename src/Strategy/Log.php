<?php

namespace Strategy;

class Log implements StrategyInterface {

	protected $type = 'log';
	

	public function getConfig() {
	    return array(
	        'type' => $this->type,
	    );
	}
	
	public function isConfigured() {
	    return true;
	}
}