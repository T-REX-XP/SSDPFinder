<?php
require(dirname(__FILE__).'/../ssdp_finder/upnp/vendor/autoload.php');
use jalder\Upnp\BinaryLight;
$BinaryLight= new BinaryLight();
$adress = $this->getProperty("CONTROLADDRESS");
$status = $this->getProperty("turnOff");
$remote = new BinaryLight\Remote($adress);
$result = $remote->off();
//print_r($result);
$this->setProperty('status', 0);
