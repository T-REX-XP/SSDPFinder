<?php
require(dirname(__FILE__).'/../ssdp_finder/upnp/vendor/jalder/upnp/src/Yeelight/Yeelight.class.php');
$adress = $this->getProperty("CONTROLADDRESS");
$status = $this->getProperty("changecolor");

$yee = new Yeelight($adress);
$yee->set_power("on"); // power on
$result = $yee->set_rgb($status); // power off
//print_r($result);
if ($result) {
    $this->setProperty('status', 1);
    } else {
    $this->setProperty('status', 0);
    $this->setProperty('alive', 0);
    say ("Выключатель ".$this->object_title." размещенный в комнате ".$this->getProperty("linkedRoom")." не сработал!", 2);
};
$yee->disconnect();

