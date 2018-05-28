<?php

require(dirname(__FILE__).'/../../../autoload.php');

use jalder\Upnp\BinaryLight;

$BinaryLight= new BinaryLight();

print('searching...'.PHP_EOL);

$BinaryLights = $BinaryLight->discover();

if(!count($BinaryLights)){
    print_r('no upnp SwitchPower found'.PHP_EOL);
}

foreach($BinaryLights as $r){
    print($r['description']['device']['friendlyName']);
    $remote = new BinaryLight\Remote($r);
    $result = $remote->off();
    print_r($result);
	sleep(1);
}
foreach($BinaryLights as $r){
    print($r['description']['device']['friendlyName']);
    $remote = new BinaryLight\Remote($r);
    $result = $remote->on();
    print_r($result);
}
