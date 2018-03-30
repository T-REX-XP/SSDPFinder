<?php

namespace jalder\Upnp;

class Renderer extends Core
{

    public function discover()
    {
       if ($type = 'AVTransport') {
        return parent::search('urn:schemas-upnp-org:service:AVTransport:1');
       }
	   if ($type = 'RenderingControl') {
        return parent::search('urn:schemas-upnp-org:service:RenderingControl:1');
       }
    }
	public function discover_ip($host)
    {
       if ($type = 'AVTransport') {
        return parent::search_ip($host, 'urn:schemas-upnp-org:service:AVTransport:1');
       }
	   if ($type = 'RenderingControl') {
        return parent::search_ip($host, 'urn:schemas-upnp-org:service:RenderingControl:1');
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
                if($device['st'] !== 'urn:schemas-upnp-org:service:AVTransport:1'){
                    unset($results[$usn]);
                }
            }
        }
        return $results;
    }
}

