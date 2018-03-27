<?php

require(dirname(__FILE__).'/../ssdp_finder/upnp/vendor/autoload.php');

use jalder\Upnp\Renderer;

$renderer = new Renderer();

$renderers = $renderer->discover();

if(!count($renderers)){
    print_r('no upnp renderers found'.PHP_EOL);
}
$uuid = $this->getProperty("UUID");
$mute_unmute = $this->getProperty("mute_unmute");
foreach($renderers as $r){
    $remote = new Renderer\Remote($r);
    if ( $mute_unmute AND $uuid == $r['description']['device']['UDN']) {
            $result = $remote->mute();
			print_r($r);
            
        } else {
            $result = $remote->unmute();
        }
echo $result;
}