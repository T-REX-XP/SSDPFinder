<?php

require(dirname(__FILE__).'/../ssdp_finder/upnp/vendor/autoload.php');

use jalder\Upnp\Renderer;

$renderer = new Renderer();

$renderers = $renderer->discover();

if(!count($renderers)){
    print_r('no upnp renderers found'.PHP_EOL);
}
$uuid = $this->getProperty("UUID");
$stop = $this->getProperty("stop");
foreach($renderers as $r){
    $remote = new Renderer\Remote($r);
    if ( $stop  AND $uuid == $r['description']['device']['UDN']) {
            $result = $remote->stop();
            $this->setProperty("stop",0);
        } else {
            $this->setProperty("stop",0);
        }
}



