<?php

require(dirname(__FILE__).'/../ssdp_finder/upnp/vendor/autoload.php');

use jalder\Upnp\Renderer;

$renderer = new Renderer();

$renderers = $renderer->discover();

if(!count($renderers)){
    print_r('no upnp renderers found'.PHP_EOL);
}
$uuid = $this->getProperty("UUID");
$pause = $this->getProperty("pause_unpause");
foreach($renderers as $r){
    $remote = new Renderer\Remote($r);
    if ( $pause AND $uuid == $r['description']['device']['UDN']) {
            $result = $remote->pause();
        } else {
            $result = $remote->unpause();
        }
}



