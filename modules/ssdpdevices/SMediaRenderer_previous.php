<?php

require(dirname(__FILE__).'/../ssdp_finder/upnp/vendor/autoload.php');

use jalder\Upnp\Renderer;

$renderer = new Renderer();

$adress = $this->getProperty("CONTROLADDRESS");
$remote = new Renderer\Remote($adress);
$previous = $this->getProperty("previous");
if ( $previous ) {
    $result = $remote->previous();
    $this->setProperty("previous",0);
} else {
    $this->setProperty("previous",0);
}
