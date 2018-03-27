<?php

$uuid = $this->getProperty("UUID");
$mute_unmute = $this->getProperty("mute_unmute");

require(dirname(__FILE__).'/../ssdp_finder/upnp/vendor/autoload.php');
use jalder\Upnp\Renderer;
$renderer = new Renderer();
$renderers = $renderer->discover_uuid($uuid);
$remote = new Renderer\Remote($renderers);
if ( $mute_unmute AND $uuid == $r['description']['device']['UDN']) {
   $result = $remote->mute();
} else {
   $result = $remote->unmute();
}
