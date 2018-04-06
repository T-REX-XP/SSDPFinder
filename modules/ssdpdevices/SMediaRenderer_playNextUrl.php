<?php

require(dirname(__FILE__).'/../ssdp_finder/upnp/vendor/autoload.php');

use jalder\Upnp\Renderer;

$renderer = new Renderer();

$adress = $this->getProperty("CONTROLADDRESS");
$remote = new Renderer\Remote($adress);
$playUrl = $this->getProperty("playNextUrl");
$remote = new Renderer\Remote($r);
if ( $playUrl) {
    $result = $remote->setNext($playUrl);
    $this->setProperty("playUrl",0);
} else {
    $this->setProperty("playUrl",0);
}