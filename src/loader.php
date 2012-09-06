<?php

/**
 * @copyright 2012
 * @author Anton Tokovoy <barss.dev@gmail.com>
 */
 
function loader($className) {
    $includeName = str_replace('\\', '/', $className);
    include $includeName . '.php';
}

spl_autoload_register('loader');