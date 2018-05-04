<?php

require(dirname(__FILE__).'/../ssdp_finder/upnp/vendor/autoload.php');

use jalder\Upnp\Renderer;

$renderer = new Renderer();

$adress = $this->getProperty("CONTROLADDRESS");
$remote = new Renderer\Remote($adress);
$playUrl = $this->getProperty("playUrl");
$info = $remote->getPosition();
$doc = new \DOMDocument();
$doc->loadXML($info);
$result = $doc->getElementsByTagName('Track');
$trackn = $result->item(0)->nodeValue;
print ($trackn);       
$result = $doc->getElementsByTagName('TrackURI');
$trackurl = $result->item(0)->nodeValue;
print ($trackurl); 
$result = $doc->getElementsByTagName('RelTime');
$tracktime = $result->item(0)->nodeValue;
print ($tracktime); 
if ( $playUrl ) {
    $result = $remote->stop();
    $result = $remote->play($playUrl);
    $result = $remote->setNext($trackurl);
    $this->setProperty("playUrl","");
} else {
    $this->setProperty("playUrl","");
}
