<?php
require(dirname(__FILE__).'/../ssdp_finder/upnp/vendor/autoload.php');
use jalder\Upnp\BinaryLight;
$BinaryLight= new BinaryLight();
$adress = $this->getProperty("CONTROLADDRESS");
$status = $this->getProperty("turnOff");
$remote = new BinaryLight\Remote($adress);
$result = $remote->off();
//print_r($result);
if ($result) {
    $this->setProperty('status', 0);
    } else {
    $this->setProperty('status', 1);
    $this->setProperty('alive', 0);
    say ("Выключатель ".$this->object_title." размещенный в комнате ".$this->getProperty("linkedRoom")." не сработал!", 2);
};
