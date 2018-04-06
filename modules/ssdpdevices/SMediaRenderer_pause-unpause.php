<?php
require(dirname(__FILE__).'/../ssdp_finder/upnp/vendor/autoload.php');

use jalder\Upnp\Renderer;

$renderer = new Renderer();

$adress = $this->getProperty("CONTROLADDRESS");
$remote = new Renderer\Remote($adress);
$pause = $this->getProperty("pause_unpause");
if ( $pause ) {
    $result = $remote->pause();
} else {
    $result = $remote->unpause();
}