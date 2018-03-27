<?php
$uuid = $this->getProperty("UUID");
$pause = $this->getProperty("pause_unpause");
require(dirname(__FILE__).'/../ssdp_finder/upnp/vendor/autoload.php');

use jalder\Upnp\Renderer;
$renderer = new Renderer();
$renderers = $renderer->discover_uuid($uuid);

if(!count($renderers)){
    print_r('no upnp renderers found'.PHP_EOL);
}

$remote = new Renderer\Remote($renderers);
if ( $pause ) {
    $result = $remote->pause();
} else {
    $result = $remote->unpause();
}




