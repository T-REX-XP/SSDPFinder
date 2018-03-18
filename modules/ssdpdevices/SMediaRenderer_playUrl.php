<?php

require(dirname(__FILE__).'/../ssdp_finder/upnp/vendor/autoload.php');

use jalder\Upnp\Renderer;

$renderer = new Renderer();

$renderers = $renderer->discover();

if(!count($renderers)){
    print_r('no upnp renderers found'.PHP_EOL);
}
$uuid = $this->getProperty("UUID");
$playUrl = $this->getProperty("playUrl");
foreach($renderers as $r){
    $remote = new Renderer\Remote($r);
    if ( $playUrl  AND $uuid == $r['description']['device']['UDN']) {
            $result = $remote->play($playUrl);
            $this->setProperty("playUrl",0);
        } else {
            $this->setProperty("playUrl",0);
        }
}



