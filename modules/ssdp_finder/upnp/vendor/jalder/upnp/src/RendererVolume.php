<?php

namespace jalder\Upnp;

class RendererVolume extends Core
{

    public function discover()
    {
	   if ($type = 'RenderingControl') {
        return parent::search('urn:schemas-upnp-org:service:RenderingControl:1');
       }
    }
    /**
     * if a previous ran upnp core search is available in memory, just filter for the renderers
     *
     */

    public function filter($results = array())
    {
        if(is_array($results)){
            foreach($results as $usn=>$device){
                if($device['st'] !== 'urn:schemas-upnp-org:service:RenderingControl:1'){
                    unset($results[$usn]);
                }
            }
        }
        return $results;
    }
}

