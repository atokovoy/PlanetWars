<?php

/**
 * @copyright 2012
 * @author Stanislav Chychkan <ch.stas@gmail.com>
 */
class ConfigManager
{


    const SOCKETS = 'sockets';
    const BROWSER = 'browser';

    protected $configsSocksMask = '*.srv';

    protected $configsSocksFile = '%s.srv';

    protected $configType;

    public function __construct($type) {
        $this->configType = $type;
    }

    public function setCacheDir($cacheDir)
    {
        $cacheDir = $cacheDir . '/' . $this->configType;

        if (!is_dir($cacheDir)) {
            mkdir($cacheDir);
        }

        $this->configsSocksMask = $cacheDir . '/*.srv';

        $this->configsSocksFile = $cacheDir . '/%s.srv';

    }




    public function clearUnused() {

		foreach (glob($this->configsSocksMask) as $filename) {
			if (filemtime($filename) < (time() - 60*5)) {
				unlink($filename);
			}
		}
        
    }




    public function getWaitedForLog() {

		foreach (glob($this->configsSocksMask) as $session) {
            $session = pathinfo($session, PATHINFO_FILENAME);
			$config = $this->read($session);
            if (!$config->getLog()) {
                return $config;
            }
		}
        return false;
    }



    public function save($config) {

        $filename = sprintf($this->configsSocksFile, $config->getSession());

        $fhandle = fopen($filename, "wb");
        fwrite($fhandle, serialize($config));
        fclose($fhandle);

        return true;

    }

    public function read($session) {

        if (!$session || !preg_match('/^[\da-zA-Z]{32}$/sm', $session)) {
            throw new Exception('Wrong session[' . $session . '] provided!');
        }

		$filename = sprintf($this->configsSocksFile, $session);

		if (!is_file($filename)) {
			throw new Exception('Config file for server not found!');
		}

        $config = unserialize(file_get_contents($filename));

        return $config;

    }


	public function generateSession() {
		$sock = $this->configsSocksFile;

		$fname = time(); $pointer = 0;
		do {
			if (1 < $pointer) {
				$name = $fname;
			} else {
				$name = $fname . '.' . $pointer;
			}
			$pointer++;
			$name = md5($name);
		} while(is_file(sprintf($sock, $name)));

		return $name;

	}
}
