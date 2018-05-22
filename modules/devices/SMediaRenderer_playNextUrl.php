<?php

require(dirname(__FILE__).'/../ssdp_finder/upnp/vendor/autoload.php');

use jalder\Upnp\Renderer;

$renderer = new Renderer();

$adress = $this->getProperty("CONTROLADDRESS");
$remote = new Renderer\Remote($adress);
$playNextUrl = $this->getProperty("playNextUrl");
if ( $playNextUrl) {
    $result = $remote->setNext($playNextUrl);
    $this->setProperty("playNextUrl",0);
} else {
    $this->setProperty("playNextUrl",0);
}
