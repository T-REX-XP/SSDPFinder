<?php
require(dirname(__FILE__).'/../ssdp_finder/upnp/vendor/autoload.php');
use jalder\Upnp\BelkinDevice;
$BelkinDevice= new BelkinDevice();
$adress = $this->getProperty("CONTROLADDRESS");
$status = $this->getProperty("turnOn");
$remote = new BelkinDevice\Devswitch($adress);
$result = $remote->on();
print_r($result);
$this->setProperty('status', 1);

