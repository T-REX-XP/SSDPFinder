<?php

namespace jalder\Upnp;

class BinaryLight extends Core
{

    public function discover()
    {
        return parent::search('urn:schemas-upnp-org:device-1-0');
    }

    public function filter($results = array())
    {
        if(is_array($results)){
            foreach($results as $usn=>$device){
                if($device['st'] !== 'urn:schemas-upnp-org:device-1-0'){
                    unset($results[$usn]);
                }
            }
        }
        return $results;

    }
}
