<?php
require(dirname(__FILE__).'/../ssdp_finder/upnp/vendor/autoload.php');
use jalder\Upnp\BelkinDevice;

$BelkinDevice= new BelkinDevice();
$adress = $this->getProperty("CONTROLADDRESS");
$status = $this->getProperty("onoff");
$remote = new BelkinDevice\Switch($adress);
if ($status){
  $result = $remote->on();
  print_r($result);
} else {
  $result = $remote->off();
  print_r($result);
};
