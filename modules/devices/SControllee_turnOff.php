<?php
require(dirname(__FILE__).'/../ssdp_finder/upnp/vendor/autoload.php');
use jalder\Upnp\BelkinDevice;
$BelkinDevice= new BelkinDevice();
$adress = $this->getProperty("CONTROLADDRESS");
$status = $this->getProperty("turnOff");
$remote = new BelkinDevice\Devswitch($adress);
$result = $remote->off();
print_r($result);
$this->setProperty('status', 0);