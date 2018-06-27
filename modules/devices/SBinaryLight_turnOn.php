<?php
require(dirname(__FILE__).'/../ssdp_finder/upnp/vendor/autoload.php');
use jalder\Upnp\BinaryLight;
$BinaryLight= new BinaryLight();
$adress = $this->getProperty("CONTROLADDRESS");
$status = $this->getProperty("turnOn");
$remote = new BinaryLight\Remote($adress);
$result = $remote->on();
//print_r($result);
$this->setProperty('status', 1);
