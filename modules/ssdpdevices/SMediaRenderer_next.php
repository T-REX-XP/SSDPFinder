<?php

require(dirname(__FILE__).'/../ssdp_finder/upnp/vendor/autoload.php');

use jalder\Upnp\Renderer;

$renderer = new Renderer();

$adress = $this->getProperty("CONTROLADDRESS");
$remote = new Renderer\Remote($adress);
$next = $this->getProperty("next");
if ( $next ) {
    $result = $remote->next();
    $this->setProperty("next",0);
} else {
    $this->setProperty("next",0);
}