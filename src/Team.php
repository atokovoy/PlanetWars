<?php

class Team {

    public $id;
	public $strategy;

	function getConfig() {
	    return array(
	        'id' => $this->id,
	        'strategy'  => $this->strategy?$this->strategy->getConfig():false,
	    );
	}
	
}