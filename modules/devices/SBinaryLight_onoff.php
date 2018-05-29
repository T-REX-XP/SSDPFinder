<?php
require(dirname(__FILE__).'/../ssdp_finder/upnp/vendor/autoload.php');
use jalder\Upnp\BinaryLight;

$BinaryLight= new BinaryLight();
$adress = $this->getProperty("CONTROLADDRESS");
$status = $this->getProperty("onoff");
$remote = new BinaryLight\Remote($adress);
if ($status){
  $result = $remote->on();
  print_r($result);
} else {
  $result = $remote->off();
  print_r($result);
};
