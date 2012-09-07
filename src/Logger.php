<?php

/**
 * @copyright 2012
 * @author Anton Tokovoy <barss.dev@gmail.com>
 */
class Logger
{
    const LEVEL_ALL = 0;
    const LEVEL_DEBUG = 10;
    const LEVEL_INFO = 20;
    const LEVEL_WARN = 30;
    const LEVEL_ERROR = 40;
    const LEVEL_FATAL = 50;
    const LEVEL_OFF = 1000;

    protected $logLevel = Logger::LEVEL_OFF;

    protected $filename;

    public function setLogLevel($level)
    {
        $this->logLevel = $level;
    }

    public function setFilename($filename)
    {
        $this->filename = $filename;
    }

    public function write($message, $level)
    {
        if (null == $this->filename) {
            return;
        }
        if ($this->logLevel > $level) {
            return;
        }
        $fp = fopen($this->filename, 'a+');
        fwrite($fp, sprintf("[%s] %s\n", date("d-m-Y H:i:s"), $message));
        fclose($fp);
    }
}
