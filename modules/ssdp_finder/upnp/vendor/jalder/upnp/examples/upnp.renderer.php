<?php

require(dirname(__FILE__).'/../../../autoload.php');

use jalder\Upnp\Renderer;

$renderer = new Renderer(); 
$remote = new Renderer\Remote('http://192.168.100.110:1370/');
$result = $remote->getMedia();
print_r($result);


