<?php

require(dirname(__FILE__).'/../ssdp_finder/upnp/vendor/autoload.php');

use jalder\Upnp\Renderer;

$renderer = new Renderer();

$renderers = $renderer->discover();

if(!count($renderers)){
    print_r('no upnp renderers found'.PHP_EOL);
}
$uuid = $this->getProperty("UUID");
$next = $this->getProperty("next");

foreach($renderers as $r){
    $remote = new Renderer\Remote($r);
    if ( $next AND $uuid == $r['description']['device']['UDN']) {
            $result = $remote->next();
            $this->setProperty("next",0);
        } else {
            $this->setProperty("next",0);
        }
}



