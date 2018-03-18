<?php

require(dirname(__FILE__).'/../ssdp_finder/upnp/vendor/autoload.php');

use jalder\Upnp\Renderer;

$renderer = new Renderer();

$renderers = $renderer->discover();

if(!count($renderers)){
    print_r('no upnp renderers found'.PHP_EOL);
}
$uuid = $this->getProperty("UUID");
$previous = $this->getProperty("previous");
foreach($renderers as $r){
    $remote = new Renderer\Remote($r);
    if ( $previous AND $uuid == $r['description']['device']['UDN']) {
            $result = $remote->previous();
            $this->setProperty("previous",0);
        } else {
            $this->setProperty("previous",0);
        }
}



