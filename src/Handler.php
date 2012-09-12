<?php


class Handler {
    
	protected $params = array();

    protected $mapsMap = '../maps/2/map%s.txt';
	
    protected $mapsMapMask = '../maps/2/map*.txt';

    protected $cacheDir = '../cache/';

    
	public function __construct($params) {
		$this->params = $params;
	}

	public function setCacheDir($cacheDir) {
		$this->cacheDir = $cacheDir;
	}
	
	public function handle() {
		if (isset($this->params['action'])
		    && '' != $this->params['action'] && ctype_alpha($this->params['action'])) {
			$method = $this->params['action'] . 'Action';		
			if (method_exists($this, $method)) {				
				return $this->$method();
			} else {
				throw new HandlerException('Action not found in handler!');
			}
		} else {
			throw new HandlerException('Action is not defined!');
		}
	}
	
	
	protected function initAction() {
	
		if (!isset($this->params['map'])) {			
		    throw new HandlerException('Map not choosen!');
		}
		
	    $map = (int) $this->params['map'];
	    $mapFile = sprintf($this->mapsMap, $map);
	    
		if (!$map || !is_file($mapFile)) {			
		    throw new HandlerException('Map [' . $map . '] not found on server!');
		}

        // load map
        $loader = new SimpleMapLoader();
        $loader->load($mapFile);
        $id = 0;
        $data = array();
        foreach ($loader as $planetDefinition) {
            $planetDefinition['id'] = $id;
            $data[] = $planetDefinition;
            $id++;
        }
		
		return array(
		    'map' => $data
		);
	}
	

	protected function serversAction() {
	    $list = array();
	    
	    //foreach (glob($this->configsSocksMask) as $filename) {
			//$config = unserialize(file_get_contents($filename));
			//if (!$config->isReady()) {
			//	$list[] = $config->getConfig();
			//}   
		//}
		
		return array(
		    'items' => $list,
		);
	}
	

	protected function mapsAction() {
	    $list = array();
	    	    		
	    foreach (glob($this->mapsMapMask) as $filename) {
			if (preg_match('/\/map(\d+)\.txt$/', $filename, $found)) {
				$list[] = $found[1];
			}   
		}
		
		return array(
		    'items' => $list,
		);
	}
	

	protected function createAction() {
		
		$teams = array();
		
		if (!isset($this->params['team'])) {			
		    throw new HandlerException('Teams not choosen!');
		}
		
		if (!isset($this->params['map'])) {			
		    throw new HandlerException('Map not choosen!');
		}
			
		$players = $this->params['team'];
		$map     = $this->params['map'];
		
		// TODO: validating map
		// TODO: validating team
		
		foreach ($players as $id => $strategy) {
			$strategy = 'Strategy\\' . $strategy;
			$team = new Team();
			$team->strategy = new $strategy;
			$team->id = $id;
			$teams[$id] = $team;
		}

        $configManager = new ConfigManager(ConfigManager::BROWSER);
        $configManager->setCacheDir($this->cacheDir);
		$session = $configManager->generateSession();
		
		$config = new Config();
		$config->setMap($map);
	    $config->setSession($session);
	    $config->setTeams($teams);

        $configManager->save($config);

        if ($config->isReady()) {
		    	
			return array(
				'success' => true,
				'message' => false,
				'data'    => array(),
			    'teams'   => $config->getTeamsConfig(),
			);
			
        } else {
		    		
			return array(
	            'session' => $session,
			);
			
		}
		
	    
	}

    protected function getlogAction() {

        //echo file_get_contents('./data/log.js');
        //die();

        $configManager = new ConfigManager(ConfigManager::SOCKETS);
        $configManager->setCacheDir($this->cacheDir);

		if (!isset($this->params['session'])) {
            $teams = array();

            if (!isset($this->params['map'])) {
                throw new HandlerException('Map not choosen!');
            }

            $map = $this->params['map'];
            foreach ($map as $planet) {
                $id = (int) $planet['player'];
                if (!isset($teams[$id])) {
                    $team = new Team();
                    $team->strategy = new Strategy\Log;
                    $team->id = $id;
                    $teams[$id] = $team;
                }
            }


            $session = $configManager->generateSession();

            $config = new Config();
            $config->setMap($map);
            $config->setSession($session);
            $config->setTeams($teams);

            $configManager->save($config);

            $log = false;

		} else {

            $session = $this->params['session'];
            $config = $configManager->read($session);

            if (!$config) {
                throw new HandlerException('Ups! Config file for server is broken! Please reload page and try again!');
            }

            $log = $config->getLog();


        }

        if ($log) {
			return array(
			    'success' => true,
				'message' => false,
			    'data'    => $log,
                'teams'   => $config->getTeamsConfig(),
			);
        } else {
			return array(
			    'success' => false,
				'message' => false,
				'session' => $session,
			);
        }

    }

	
	protected function joinAction() {
				
		if (!isset($this->params['session'])) {			
		    throw new HandlerException('Session not choosen!');
		}

        $configManager = new ConfigManager(ConfigManager::BROWSER);
        $configManager->setCacheDir($this->cacheDir);
        
		$config = $configManager->read($this->params['session']);

        if (!$config) {
			throw new HandlerException('Ups! Config file for server is broken! Please reload page and try again!');
		}
		
		if (isset($this->params['team'])) {			

		    if ($config->isReady()) {
		    	
			    return array(
				    'success' => true,
					'message' => false,
				    'data'    => array(),
			    	'teams'   => $config->getTeamsConfig(),
				);
			
		    } else {
		    		
			    return array(
					'message' => false,
			        'wait' => $config->isReady(),
				);
			
		    }
		
		} else {
			
		
			if (!isset($this->params['strategy']) || '' == $this->params['strategy']) {			
			    throw new HandlerException('Strategy not choosen!');
			}
			// TODO: validating
			$strategy = $this->params['strategy'];
			
			$team = $config->getNotReadyTeam();
			
			if (false === $team) {
				throw new HandlerException('Ups! All teams binded! Please search for another server!');
			}
			
			$strategy = new Strategy\Bot($strategy);
			
			$steam = new Team();
			$steam->strategy = $strategy;
			$steam->id = $team;
				
			$config->setTeam($team, $steam);
			
            $configManager->save($config);

		    return array(
				'message' => false,
		    	'team'   => $team,
				'data'    => array(),
			);
			
		}
			
	}
	
}