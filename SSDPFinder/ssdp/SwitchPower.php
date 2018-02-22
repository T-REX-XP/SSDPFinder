<?php

require_once('core.php');

class SwitchPower extends Core
{

    public function discover()
    {
        return parent::search('urn:schemas-upnp-org:device:BinaryLight:1');
    }

    public function filter($results = array())
    {
        if(is_array($results)){
            foreach($results as $usn=>$device){
                if($device['st'] !== 'urn:schemas-upnp-org:device:BinaryLight:1'){
                    unset($results[$usn]);
                }
            }
        }
        return $results;

    }
}
