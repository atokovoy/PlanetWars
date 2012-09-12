<?php

require_once('../loader.php');

sleep(1);

try {
    $handler =  new Handler($_POST);
    $handler->setCacheDir('../cache/');
	$res = $handler->handle();
} catch (HandlerException $e) {
	$res = array('error' => $e->getMessage());
} catch (Exception $e) {
    $res = array('error' => $e->getMessage());
}


die(json_encode($res));