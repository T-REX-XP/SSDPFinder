<?php

namespace jalder\Upnp;

class BelkinDevice extends Core
{

    public function discover()
    {
        return parent::search('urn:Belkin:device-1-0');
    }

    public function filter($results = array())
    {
        if(is_array($results)){
            foreach($results as $usn=>$device){
                if($device['st'] !== 'upnp:rootdevice'){
                    unset($results[$usn]);
                }
                if($device['st'] !== 'urn:Belkin:device:**'){
                    unset($results[$usn]);
                } 
                if($device['st'] !== 'urn:Belkin:device:bridge:1'){
                      unset($results[$usn]);
                }
               if($device['st'] !== 'urn:Belkin:device:controllee:1'){
                      unset($results[$usn]);
                 }
                if($device['st'] !== 'urn:Belkin:device:sensor:1'){
                     unset($results[$usn]);
                 }
                if($device['st'] !== 'urn:Belkin:device:Maker:1'){
                     unset($results[$usn]);
                 }
                if($device['st'] !== 'urn:Belkin:device:insight:1'){
                     unset($results[$usn]);
                 }
                if($device['st'] !== 'urn:Belkin:device:lightswitch:1'){
                     unset($results[$usn]);
                 }
            }
        }
        return $results;

    }
}
