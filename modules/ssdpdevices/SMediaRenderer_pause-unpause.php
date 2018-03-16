<?php

require(dirname(__FILE__).'/upnp/vendor/autoload.php');

use jalder\Upnp\Renderer;

$renderer = new Renderer();

$renderers = $renderer->discover();

if(!count($renderers)){
    print_r('no upnp renderers found'.PHP_EOL);
}
$pause = $this->getProperty("pause_unpause");

foreach($renderers as $r){

    print($r['description']['device']['friendlyName']);
    $remote = new Renderer\Remote($r);
    if ( $pause) {
            $result = $remote->pause();
        } else {
            $result = $remote->unpause();
        }
    print_r($result);
}



