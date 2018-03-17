<?php

require(dirname(__FILE__).'/../ssdp_finder/upnp/vendor/autoload.php');

use jalder\Upnp\Renderer;

$renderer = new Renderer();

$renderers = $renderer->discover();

if(!count($renderers)){
    print_r('no upnp renderers found'.PHP_EOL);
}
$previous = $this->getProperty("previous");

foreach($renderers as $r){

    print($r['description']['device']['friendlyName']);
    $remote = new Renderer\Remote($r);
    if ( $pause) {
            $result = $remote->previous();
            $this->setProperty("previous",0);
        } else {
            $this->setProperty("previous",0);
        }
}



