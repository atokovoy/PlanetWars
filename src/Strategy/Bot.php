<?php

namespace Strategy;

class Bot implements StrategyInterface {

	protected $url;

	protected $type = 'bot';
	
	public function __construct($url) {
		$this->url = $url;
	}

	public function getConfig() {
	    return array(
	        'type' => $this->type,
	        'url'  => $this->url,
	    );
	}
	
	public function isConfigured() {
	    return true;
	}
}