<?php

require(dirname(__FILE__).'/../ssdp_finder/upnp/vendor/autoload.php');

use jalder\Upnp\Renderer;

$adress = $this->getProperty("CONTROLADDRESS");
$remote = new Renderer\RemoteVolume($adress);
$volume = $this->getProperty("volume");
$result = $remote->SetVolume($volume);
