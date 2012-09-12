<?php

class Config {

    protected $map;

	protected $teams;
	
    protected $session;

    protected $log;

	public function __construct() {
		
	}

	public function setMap($map) {
		$this->map = $map;
	}
    
	public function getMap() {
	    return	$this->map;
	}
	
	public function setSession($session) {
		$this->session = $session;
	}
	
	public function getSession() {
		return $this->session;
	}

	public function setLog($log) {
		$this->log = $log;
	}

	public function getLog() {
		return $this->log;
	}
	
	public function getTeamsCount() {
		return count($this->teams);
	}

	public function setTeams($teams) {
		$this->teams = $teams;
	}

	public function setTeam($id, $team) {
		$this->teams[$id] = $team;
	}

	public function isReady() {
		foreach($this->teams as $team) {
			if (!$team->strategy->isConfigured()) return false;
		}
		return true;
	}

    public function getWaitedForLog() {
		foreach($this->teams as $key => $team) {
			if (!$team->strategy->isConfigured()) return $key;
		}
		return false;
    }

	public function getNotReadyTeam() {
		foreach($this->teams as $key => $team) {
			if (!$team->strategy->isConfigured()) return $key;
		}
		return false;
	}
	
	public function getConfig() {
		return array(
		    'session' => $this->getSession(),
		    'teams' => $this->getTeamsConfig(),
		);
	}
	
	public function getTeamsConfig() {
		$teams = array();
		foreach($this->teams as $team) {
			$teams[] = $team->getConfig();
		}
		return $teams;
	}
	
}