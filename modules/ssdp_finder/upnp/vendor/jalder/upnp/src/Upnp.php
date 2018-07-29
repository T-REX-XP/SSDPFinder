<?php
/**
 * @author Jalder
 * Upnp class for interacting with UPnP network devices using PHP socket connections
 *
 * Derived from @author Morten Hekkvang <artheus@github>
 *
 */

namespace jalder\Upnp;

class Upnp extends Core{

    public function __construct()
    {

    }

    public function discover()
    {   
        return parent::search();
    }
	
	public function discoveryeelight()
    {   
        return parent::searchyeelight();
    }
	
    public function discover_ip($host)
    {   if (!$host) {
         $host = '239.255.255.250';
         }
        return parent::search_ip($host);
    }
    public function alive()
    {
        return (bool)count($this->discover($data));
    }

}

