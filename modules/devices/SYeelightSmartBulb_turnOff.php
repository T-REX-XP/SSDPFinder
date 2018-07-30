<?php
require(dirname(__FILE__).'/../ssdp_finder/upnp/vendor/jalder/upnp/src/Yeelight/Yeelight.class.php');
$adress = $this->getProperty("CONTROLADDRESS");
$status = $this->getProperty("turnOff");

$yee = new Yeelight($adress);
$result = $yee->set_power("off"); // power off
//print_r($result);
if ($result) {
    $this->setProperty('status', 0);
    } else {
    $this->setProperty('status', 1);
    $this->setProperty('alive', 0);
    say ("Выключатель ".$this->object_title." размещенный в комнате ".$this->getProperty("linkedRoom")." не сработал!", 2);
};
$yee->disconnect();


//<?php
//require "Yeelight.class.php";

//$yee = new Yeelight("10.0.0.201", 55443);

//$yee->set_power("on"); // power on
//$yee->set_rgb(0xFF0000); // color to red
//$yee->set_bright(50); // brightness to 50%

//$yee->commit(); // changes are not sent to the bulb before commit() is called

//sleep(10);
//$yee->set_rgb(0x00FF00)->set_bright(100)->commit(); // calls return the object for fast chaining of commands

//$status = $yee->get_prop("power")->commit(); // get current status
//print_r($status);

//$yee->disconnect();
