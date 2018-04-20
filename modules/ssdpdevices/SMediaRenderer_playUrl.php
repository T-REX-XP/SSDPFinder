<?php

require(dirname(__FILE__).'/../ssdp_finder/upnp/vendor/autoload.php');

use jalder\Upnp\Renderer;

$renderer = new Renderer();

$adress = $this->getProperty("CONTROLADDRESS");
$remote = new Renderer\Remote($adress);
$playUrl = $this->getProperty("playUrl");
if ( $playUrl ) {
    $result = $remote->play($playUrl);
    $this->setProperty("playUrl","");
} else {
    $this->setProperty("playUrl","");
}
